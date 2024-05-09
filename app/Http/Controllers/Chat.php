<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class Chat extends Controller
{
	public function index( Request $request )
	{
		$body = 'login';
		$chatrooms = [];
		if( !empty( Session::get('chat')) )
		{
			$body = 'viewChat';
			$chatrooms = DB::table('chats')
				->select('chats.*', 'chatMessages.*')
				->leftJoin('chatMessages', 'chats.chatId', '=', 'chatMessages.chatId')
				->where( 'chats.chatId', Session::get('chat')->chatId)
				->get()
			;
		}
		elseif( Session::get('user') )
		{
			$body = 'chats';
			$chatrooms = DB::table('chats')
				->select('chats.chatId', 'chats.chatName', 'chats.chatDescription', 'chats.userName', DB::raw('COUNT(chatMessages.chatMessageId) as chatCount'))
				->leftJoin('chatMessages', 'chats.chatId', '=', 'chatMessages.chatId')
				->groupBy('chats.chatId')
				 ->orderBy('chats.chatId', 'desc')
				->get()
			;
		}
		return view( 'chatroom', [ 'body' => $body, 'chatrooms' => $chatrooms ] );
	}

	public function loginPost(Request $request)
    {
        $username = $request->username;

		if( strlen( $username ) < 3 )
		{
			return redirect()->back()->withErrors(['error' => $username . ' is too short, please choose a name 3 or longer.']);
		}

        $user = DB::table('users')->where('userName', 'LIKE', '%' . $username )->first();
        if( !empty( $user ) ) {
			return redirect()->back()->withErrors(['error' => $username . ' is an active user, please choose another name.']);
        }

		$minutes = 60 * 24 * 30; // Cookie will be set for 30 days
		$id = DB::table('users')->insertGetId(['userName' => $username, 'userExpiration' => date( 'Y-m-d H:i:s', strtotime( '+1 month', time() ) )]);
		Session::put('user', DB::table('users')->where('userId', $id)->first());
		return redirect('/')->withCookie( Cookie::make('userId', $id, $minutes) )->with('success', 'You have succefully logged in and will be remembered.');
    }
	
	public function createChat(Request $request)
	{
		if( !Session::get('user') )
		{
			return redirect()->back()->withErrors(['error' => 'You are not logged in, please login to create a chatroom']);
		}

		$id = DB::table('chats')->insertGetId(
			[
				'chatName' => $request->chatName,
				'chatDescription' => $request->description,
				'userName' => Session::get('user')->userName,
			]
		);
		
		DB::table('chatMessages')->insert(
			[
				'chatId' => $id,
				'chatMessageText' => $request->firstMessage,
				'userName' => Session::get('user')->userName,
			]
		);
		
		Session::put('chat', DB::table('chats')->where( 'chats.chatId', $id)->first());
		return redirect('/');
	}
	
	public function addMessage(Request $request)
	{
		if( !Session::get('user') or !Session::get('chat') )
		{
			return null;
		}

		DB::table('chatMessages')->insert(
			[
				'chatId' => Session::get('chat')->chatId,
				'chatMessageText' => $request->message,
				'userName' => Session::get('user')->userName,
			]
		);
		
		return true;
	}
	
	public function viewChat( Request $request )
	{
		$chatrooms = DB::table('chats')
			->select('chats.chatName', 'chats.chatDescription', 'chatMessages.*')
			->leftJoin('chatMessages', 'chats.chatId', '=', 'chatMessages.chatId')
			->where( 'chats.chatId', $request->id)
			->get()
		;
		
		Session::put('chat', DB::table('chats')->where( 'chats.chatId', $request->id)->first());
		return view('viewChat', ['chatrooms' => $chatrooms])->render();
	}
	
	public function home( Request $request )
	{
		$body = 'login';
		$chatrooms = [];
		if( Session::get('user') )
		{
			$body = 'chats';
			$chatrooms = DB::table('chats')
				->select('chats.chatId', 'chats.chatName', 'chats.chatDescription', 'chats.userName', DB::raw('COUNT(chatMessages.chatMessageId) as chatCount'))
				->leftJoin('chatMessages', 'chats.chatId', '=', 'chatMessages.chatId')
				->groupBy('chats.chatId')
				 ->orderBy('chats.chatId', 'desc')
				->get()
			;
		}
		Session::forget('chat');
		return view( $body, [ 'chatrooms' => $chatrooms ] );
	}
	
	public function logout( Request $request )
	{
		DB::table('users')->where('userId', Session::get('user')->userId)->delete();
		Session::forget('user');
		Session::forget('chat');
		
		return redirect('/')->withCookie(cookie()->forget('userId'));
	}
	
	public function checkUpdates( Request $request )
	{
		$newCount = DB::table('chatMessages')->where( 'chatId', Session::get('chat')->chatId)->count() - (int) $request->number;
		if( $newCount > 0 )
		{
			return DB::table('chatMessages')
				->where( 'chatId', Session::get('chat')->chatId)
				->orderBy( 'chatMessageId', 'desc' )
				->limit( $newCount )
				->get()
			;
		}
		
		return false;
	}
}
