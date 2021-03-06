<?php

namespace App\Http\Controllers;

use App\Notifications\PushRegistered;
use App\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\PushSubscription;

class PushNotificationController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];

        $subUserExist = PushSubscription::findByEndpoint($endpoint);

        $completelyNew = false;

        if ($subUserExist) {
            $notificationUser = NotificationUser::find($subUserExist->subscribable->id);
        } else {
            $notificationUser = NotificationUser::create();
            $completelyNew = true;
        }

        $notificationUser->updatePushSubscription($endpoint, $key, $token);

        if ($completelyNew) {
            Notification::send($notificationUser, new PushRegistered());
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Send Push Notifications to all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function push()
    {
        Notification::send(NotificationUser::all(), new PushRegistered());

        return response()->json(['success' => true], 200);
    }

    /**
     * Delete the specified subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
        ]);

        $endpoint = $request->endpoint;

        $subUserExist = PushSubscription::findByEndpoint($endpoint);

        $notificationUser = NotificationUser::find($subUserExist->subscribable->id);

        $notificationUser->deletePushSubscription($endpoint);

        $notificationUser->delete();

        return response()->json(['success' => true], 204);
    }
}
