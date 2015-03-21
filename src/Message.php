<?php

    class Message {
        private $texts;

        function __construct($new_text)
        {
            $this->texts = $new_text;
        }

        function getText()
        {
            return $this->texts;
        }

        function setText($new_text)
        {
            $this->texts = $new_text;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO messages (messages) VALUES ('{$this->getText()}');");
        }

        static function getAll()
        {
            $all_messages_pdo = $GLOBALS['DB']->query("SELECT * FROM messages");
            $return_messages = array ();
            {
                foreach ($all_messages_pdo as $element)
                {
                    $new_message = $element['messages'];
                    $new_object = new Message($new_message);
                    array_push($return_messages, $new_object);
                }
                return $return_messages;
            }
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM messages *;");
        }
    }
?>
