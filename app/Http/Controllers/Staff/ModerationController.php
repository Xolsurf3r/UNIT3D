<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Torrent;
use App\TorrentRequest;
use App\PrivateMessage;
use App\Helpers\TorrentHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use \Toastr;

class ModerationController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Torrent Moderation Panel
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function moderation()
    {
        $current = Carbon::now();
        $pending = Torrent::pending()->get();
        $postponed = Torrent::postponed()->get();
        $rejected = Torrent::rejected()->get();
        $modder = Torrent::where('status', 0)->count();

        return view('Staff.torrent.moderation', [
            'current' => $current,
            'pending' => $pending,
            'postponed' => $postponed,
            'rejected' => $rejected,
            'modder' => $modder
        ]);
    }

    /**
     * Approve A Torrent
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function approve($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->where('id', '=', $id)->where('slug', '=', $slug)->first();

        if ($torrent->status !== 1) {
            $appurl = config('app.url');
            $user = $torrent->user;
            $user_id = $user->id;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : User [url={$appurl}/" . $username . "." . $user_id . "]" . $username . "[/url] has uploaded [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"
                );
            } else {
                $this->chat->systemMessage(
                    ":robot: [b][color=#fb9776]System[/color][/b] : An anonymous user has uploaded [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"
                );
            }

            TorrentHelper::approveHelper($torrent->slug, $torrent->id);

            return redirect()->route('moderation')
                ->with(Toastr::success('Torrent Approved', 'Yay!', ['options']));
        } else {
            return redirect()->back()
                ->with(Toastr::error('Torrent Already Approved', 'Whoops!', ['options']));
        }
    }

    /**
     * Postpone A Torrent
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function postpone(Request $request)
    {
        $v = validator($request->all(), [
            'id' => "required|exists:torrents",
            'slug' => "required|exists:torrents",
            'message' => "required"
        ]);

        if ($v->fails()) {
            return redirect()->route('moderation')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $user = auth()->user();
            $torrent = Torrent::withAnyStatus()->where('id', $request->input('id'))->first();
            $torrent->markPostponed();

            $pm = new PrivateMessage;
            $pm->sender_id = $user->id;
            $pm->receiver_id = $torrent->user_id;
            $pm->subject = "Your upload, {$torrent->name} ,has been postponed by {$user->username}";
            $pm->message = "Greetings, \n\n Your upload, {$torrent->name} ,has been postponed. Please see below the message from the staff member. \n\n{$request->input('message')}";
            $pm->save();

            return redirect()->route('moderation')
                ->with(Toastr::success('Torrent Postponed', 'Yay!', ['options']));
        }
    }

    /**
     * Reject A Torrent
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request)
    {
        $v = validator($request->all(), [
            'id' => "required|exists:torrents",
            'slug' => "required|exists:torrents",
            'message' => "required"
        ]);

        if ($v->fails()) {
            return redirect()->route('moderation')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $user = auth()->user();
            $torrent = Torrent::withAnyStatus()->where('id', $request->input('id'))->first();
            $torrent->markRejected();

            $pm = new PrivateMessage();
            $pm->sender_id = $user->id;
            $pm->receiver_id = $torrent->user_id;
            $pm->subject = "Your upload, {$torrent->name} ,has been rejected by {$user->username}";
            $pm->message = "Greetings, \n\n Your upload {$torrent->name} has been rejected. Please see below the message from the staff member. \n\n{$request->input('message')}";
            $pm->save();

            return redirect()->route('moderation')
                ->with(Toastr::success('Torrent Rejected', 'Yay!', ['options']));
        }
    }

    /**
     * Resets the filled and approved attributes on a given request
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function resetRequest($id)
    {
        $user = auth()->user();

        if ($user->group->is_modo) {
            $torrentRequest = TorrentRequest::findOrFail($id);
            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->approved_by = null;
            $torrentRequest->approved_when = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::success("The request has been reset!", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])
                ->with(Toastr::error("You don't have access to this operation!", 'Whoops!', ['options']));
        }
    }
}
