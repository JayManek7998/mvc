<?php
require_once 'Model/Core/Message';


$messagesModel = new Model_Core_Message();
$messages = $messages->getMessage();
$messages = $messages->clearMessage();

foreach ($messages as $type => $message):
?> 

<h2><?php echo $messages; ?></h2>
<?php
end foreach;
?>
	