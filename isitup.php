<? php
	/*
	* Tutorial:
	* https://github.com/mccreath/isitup-for-slack/blob/e230e0e0e2e4c9c827d72c6331d9d78c2a37e706/docs/TUTORIAL.md
	*/

# User Agent String
$user_agent = "IsitupMKGForSlack/1.0 (https://github.com/tsaomao/Tutorial-SlackSlashCommand/blob/master/isitup.php; malcolm.gin@gmail.com)";

# Initialize
$command = $_POST['command'];
$domain = $_POST['text'];
$token = $_POST['token'];

# Check incoming token for validity.
# If repurposing, be sure to check the token against the token
# for YOUR PARTICULAR slash command TOKEN.
if($token != 'bszTdbtgr11nGnlYpkIACZtB') {
	$msg = "The token for this slash command doesn't match. Check script.";
	die($msg);
	echo $msg;
}

# Establish isitup.org link.
# isitup.org should validate given domain and return 3 if domain
# is invalid. Also we request the JSON response so we can parse it more easily.
$check_url = "https://isitup.org/".$domain.".json";

# Initialize cURL request
$ch = curl_init($url_to_check);

# Set up cURL request with our variables
# - Set User Agent
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
# - Tell cURL that we expect return data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

# Call cURL:
$ch_response = curl_exec($ch);

# Close cURL:
curl_close($ch)

# Parse response into a json array
$response_array = json_decode($ch_response, TRUE);

if($ch_response == FALSE) {
	# isitup.org Down?
	$reply = "Weird. isitup.org could not be reached.";
} else {
	# Yay. Code 1 means up.
	if ($response_array['status_code'] == 1) {
		$reply = ":thumbsup: Yay! ".$response_array['domain']." is up!";
	# Boo. Code 2 means down.
	} else if ($response_array['status_code'] == 2) {
		$reply = ":disappointed: On no! ".$response_array['domain']." is down...";
	# Code 3 means Invalid Domain.
	} else if ($response_array['status_code'] == 3) {
		$reply = ":interrobang: IsItUp.org says your domain is invalid. ";
		$reply .= "Please enter both the domain and the suffix (e.g. amazon.com or whitehouse.gov).";
	} else {
		$reply = "Got a weird response from isitup: ".$response_array['status_code']
	}
}
echo $reply;
?>
