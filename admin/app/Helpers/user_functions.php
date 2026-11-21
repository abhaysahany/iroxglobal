<?php 
use App\Models\MemberDetail;

function getName($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();
    return $member->name;
}

?>