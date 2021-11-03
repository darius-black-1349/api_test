<?php

namespace App\Http\Controllers\Api\v1\Channel;

use App\Channel;
use App\Http\Controllers\Controller;
use App\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    public function getAllChannelsList()
    {

        $all_channels = resolve(ChannelRepository::class)->all();

        return response()->json($all_channels, Response::HTTP_OK);
    }

    public function createNewChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

       resolve(ChannelRepository::class)->create($request->name);

        return response()->json([
            'message' => 'channel created successfully'
        ], Response::HTTP_CREATED);
    }

    public function updateChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);


        resolve(ChannelRepository::class)->update($request->id, $request->name);



        return response()->json([
            'message' => 'channel edited successfully'
        ], Response::HTTP_OK);
    }
}
