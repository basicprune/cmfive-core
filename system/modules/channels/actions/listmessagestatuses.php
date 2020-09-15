<?php

function listmessagestatuses_ALL(Web $w)
{
    $p = $w->pathMatch("id");
    $id = $p["id"];
    ChannelsService::getInstance($w)->navigation($w, "Message Statuses");

    if (!$id) {
        $w->error("Message ID not found", "/channels/listmessages");
    }

    $messagestatuses = ChannelService::getInstance($w)->getMessageStatuses($id);

    $w->ctx("statuses", $messagestatuses);
}
