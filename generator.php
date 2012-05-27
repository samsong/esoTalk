<html>
<head>
<title></title>
</head>
<body>
<h1>esoTalk generator</h1>
<?php

mysql_connect("localhost", "forum", "forum");
mysql_selectdb("forum");
echo mysql_error();

set_time_limit(0);

$numMembers = 3000;
$numConversations = 2000;
$numPosts = 5000;

$time = time();

echo "Reading dictionary...<br/>"; flush();
$dictionary = file("dictionary.txt", FILE_IGNORE_NEW_LINES);
$dictionaryCount = count($dictionary);
$wordStrings = array();
$countWordStrings = 100;
for ($i = 0; $i < $countWordStrings; $i++) {
	$amount = mt_rand(10, 200);
	$string = "";
	for ($j = 0; $j < $amount; $j++) {
		$string .= $dictionary[mt_rand(0, $dictionaryCount)] . " ";
	}
	$wordStrings[] = $string;
}
echo "Done reading dictionary!<br/>"; flush();

function randomWords($count)
{
	global $dictionary, $dictionaryCount;
	$string = "";
	for ($i = 0; $i < $count; $i++) {
		$string .= $dictionary[mt_rand(0, $dictionaryCount)] . " ";
	}
	return $string;
}

function makeRandomDateInclusive($startDate,$endDate){
    $days = round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
    $n = mt_rand(0,$days);
    return strtotime("$startDate + $n days");
}

function randomTime()
{
	#return rand(1000000000, 1400000000);
	return makeRandomDateInclusive('2006-04-01','2012-05-26');
}

 $uMembers = 0;
 $uConversations = 0;
 $uPosts = 1;
 
 // Members!
 if($uMembers)
 {
 echo "Inserting $numMembers members...<br/>"; flush();
 $inserts = array();
 mysql_query("DELETE FROM et_members where id > 2");
 mysql_query("ALTER TABLE et_member AUTO_INCREMENT = 3");
 for ($i = 0; $i < $numMembers; $i++) {
 	$inserts[] = "('".substr(md5(mt_rand()), 0, 10)."', '".md5(mt_rand())."', 'member', '1', 'asdf', null,  ".randomTime().")";
 	if (count($inserts) == 1000) {
 		mysql_query("INSERT INTO et_member (username, email, account, confirmedEmail,  password, resetPassword, joinTime) VALUES ".implode(",", $inserts));
 		$inserts = array();
 		echo "$i... "; flush();
 	}
 }
 echo mysql_error()."<br/>";
 echo "Done members!<br/>"; flush();
}

 if($uConversations)
 {
 // Conversations!
 echo "Inserting $numConversations conversations...<br/>"; flush();
 $inserts = array();
 mysql_query("DELETE FROM et_conversation");
 mysql_query("ALTER TABLE et_conversation AUTO_INCREMENT = 1");
 for ($i = 0; $i < $numConversations; $i++) {
 	$inserts[] = "('".randomWords(mt_rand(2,5))."', ".rand(1,8).", 0 , 0 , 0 ,".rand(1,$numMembers).", ".randomTime().", ".rand(1,$numMembers).", ".randomTime().", 1)";
 	if (count($inserts) == 1000) {
 		mysql_query("INSERT INTO et_conversation (title, channelId, private, sticky, locked, startMemberId, startTime, lastPostMemberId, lastPostTime, countPosts) VALUES ".implode(",", $inserts));
 		$inserts = array();
 		echo "$i... "; flush();
 	}
 }
 echo mysql_error()."<br/>";
 echo "Done conversations!<br/>"; flush();
}

if($uPosts)
{
// Posts!
echo "Inserting $numPosts posts...<br/>"; flush();
 $inserts = array();
 mysql_query("DELETE FROM et_post");
 mysql_query("ALTER TABLE et_post AUTO_INCREMENT = 1");
 for ($i = 0; $i < $numPosts; $i++) {
 	$inserts[] = "(".rand(1, $numConversations).", ".rand(1, $numMembers).", ".randomTime().", '".randomWords(mt_rand(10,80))."')";
 	#echo "(".rand(1, $numConversations).", ".rand(1, $numMembers).", ".randomTime().", '".randomWords(mt_rand(50,200))."')";
 	#exit;
 	if (count($inserts) == 100) {
 		mysql_query("INSERT INTO et_post (conversationId, memberId, time, content) VALUES ".implode(",", $inserts));
 		$inserts = array();
 		echo "$i... "; flush();
 	}
 }
 echo mysql_error()."<br/>";
 echo "Done posts!<br/>"; flush();

 echo "Finishing off...<br/>"; flush();
 mysql_query("UPDATE et_conversation SET countPosts=(SELECT COUNT(postId) FROM et_post WHERE et_post.conversationId=et_conversation.conversationId)");
 mysql_query("UPDATE et_post SET title=(SELECT title FROM et_conversation WHERE et_post.conversationId=et_conversation.conversationId)");
 echo mysql_error()."<br/>";
 }
	//update et_conversation set startMemberId = FLOOR( 1 + RAND( ) *32000 );
	//update et_conversation set lastPostMemberId = FLOOR( 1 + RAND( ) *32000 );
	//update et_post set memberId = FLOOR( 1 + RAND( ) *32000 );
	
 echo "Done!<br/>"; flush();

?>
</body>
</html>