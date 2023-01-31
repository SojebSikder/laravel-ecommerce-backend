<?php

namespace App\Services\Newsletter;

use App\Models\Newsletter\MailingList;

class MailingListService
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($fname = null, $lname = null, $email = null, $user_id = null)
    {
        try {
            $uuid = uniqid('m', true);

            $existMail = MailingList::where('email', $email)->first();

            if (!$existMail) {
                $mailingList = new MailingList();
                if ($user_id) {
                    $mailingList->user_id = $user_id;
                } else if (auth('api')->user()) {
                    $mailingList->user_id = auth('api')->user()->id;
                }
                if ($fname) {
                    $mailingList->fname = $fname;
                }
                if ($lname) {
                    $mailingList->lname = $lname;
                }
                $mailingList->email = $email;
                $mailingList->uuid = $uuid;
                $mailingList->save();

                return $mailingList;
            } else {
                return $existMail;
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
