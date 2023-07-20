<?php

class MessagesStorage
{

    private array $messages = [];
    
    public function __construct()
    {
        $this->createMessagesFileIfNotExists();
        $this->parceFile();
    }
    function createMessagesFileIfNotExists()
    {
        if (!file_exists(MESSAGES_FILE)) {
            file_put_contents(MESSAGES_FILE, "{}");
        }
    }
    function parceFile(){
        $fileText = file_get_contents(MESSAGES_FILE);
        $this->messages = json_decode($fileText, true);
    }


    public function getMessages() 
    {
        return $this->messages;
    }


    public function addMessage($message)
    {
        $this->messages[] = $message;
    }


    public function __destruct()
    {
        $this->saveMessages();
    }

    private function saveMessages()
    {
        file_put_contents(MESSAGES_FILE, json_encode($this->messages));
    }
}
