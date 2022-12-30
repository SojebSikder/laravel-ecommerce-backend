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
    public function store($email = null, $user_id = null)
    {
        try {
            $uuid = uniqid('m', true);

            $mailingList = new MailingList();
            if ($user_id) {
                $mailingList->user_id = $user_id;
            } else if (auth('api')->user()) {
                $mailingList->user_id = auth('api')->user()->id;
            }
            $mailingList->email = $email;
            $mailingList->uuid = $uuid;
            $mailingList->save();

            return $mailingList;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
