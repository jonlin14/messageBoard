<?php
        require_once __DIR__."/../vendor/autoload.php";
        require_once __DIR__."/../src/Message.php";
        use Symfony\Component\HttpFoundation\Request;
        Request::enableHttpMethodParameterOverride();

        $DB = new PDO('pgsql:host=localhost;dbname=messages');

        $app = new Silex\Application;
        $app['debug'] = true;

        $app->register(new Silex\Provider\TwigServiceProvider(), array (
            'twig.path'=>__DIR__.'/../views'
        ));

        $app->get("/", function() use($app) {
            return $app['twig']->render('message.html.twig', array ('messages' => Message::getAll()));
        });

        $app->post("/", function() use($app) {
            $new_text = $_POST['input_text'];
            $new_message = new Message($new_text);
            $new_message->save();

            return $app['twig']->render('message.html.twig', array ('messages' => Message::getAll()));
        });

        $app->post("/delete", function() use($app) {
            Message::deleteAll();

            return $app['twig']->render('message.html.twig', array ('messages' => Message::getAll()));
        });

        $app->patch("/update/{id}", function($id) use ($app) {
          $message = Message::find($id);
          $message->update($_POST['new_text']);
          return $app['twig']->render('message.html.twig', array ('messages' => Message::getAll()));
        });

        $app->get("/message/{id}", function($id) use($app) {
          $message = Message::find($id);
          return $app['twig']->render('message_edit.html.twig', array ('message' => $message));
        });

        $app->delete("/delete/{id}", function($id) use ($app) {
          $message = Message::find($id);
          $message->delete();
          return $app['twig']->render('message.html.twig', array ('messages' => Message::getAll()));
        });

        return $app;

 ?>
