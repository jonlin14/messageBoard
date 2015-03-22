<?php

    class Message {
        private $texts;
        private $id;

        function __construct($new_text, $id = null)
        {
            $this->texts = $new_text;
            $this->id = $id;
        }

        function getText()
        {
            return $this->texts;
        }

        function setText($new_text)
        {
            $this->texts = $new_text;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO messages (messages) VALUES ('{$this->getText()}') RETURNING id;");
            $result = $statement->FETCH(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        function update($new_message)
        {
            $GLOBALS['DB']->exec("UPDATE messages SET messages = '{$new_message}' WHERE id = {$this->getId()};");
            $this->setText($new_message);
        }

        function delete()
        {
          $GLOBALS['DB']->exec("DELETE FROM messages WHERE id = {$this->getId()};");
        }
        static function getAll()
        {
            $all_messages_pdo = $GLOBALS['DB']->query("SELECT * FROM messages ORDER BY id");
            $return_messages = array ();
            {
                foreach ($all_messages_pdo as $element)
                {
                    $new_message = $element['messages'];
                    $new_id = $element['id'];
                    $new_object = new Message($new_message, $new_id);
                    array_push($return_messages, $new_object);
                }
                return $return_messages;
            }
        }



        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM messages *;");
        }

        static function find($search_id)
        {
            $all_messages = Message::getAll();
            $found_message = null;
            foreach ($all_messages as $element)
            {
              if ($element->getId() == $search_id)
              {
                $found_message = $element;
              }
            }
            return $found_message;
        }
    }
?>
