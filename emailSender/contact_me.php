<?php
header('Content-Type: text/html; charset=utf-8');
if ($_POST) {
    $to_Email = "pomykacz.sz@gmail.com"; //Podaj tu email docelowy
    $subject = 'Formularz kontaktowy z Portfolio'; //Tutaj temat wiadomości - możesz też wykorzystać pole formularza i pobrać tą wartość od klienta :)


    //Sprawdzamy czy jest to rządanie Ajax, jeśli nie..
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {

        //Kończymy skrypt wysyłając dane JSON
        $output = json_encode(
            array(
                'type' => 'error',
                'text' => 'Rządanie musi przejść przez AJAX'
            ));

        die($output);
    }

    //Sprawdzamy czy wszystkie pola zostały wysłane. kończymy skrypt jeśli nie (tutaj dodawaj więcej pól, które są wymagane)
    if (!isset($_POST["userEmail"]) || !isset($_POST["userMessage"])) {
        $output = json_encode(array('type' => 'error', 'text' => 'Pola są puste.'));
        die($output);
    }

    //Pobieramy dane z formularza
    $user_Name = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
    $user_Email = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $user_Message = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
    $form_type = filter_var($_POST["form"], FILTER_SANITIZE_STRING);
    //Dodatkowa validacja PHP (tylko dla pól wymaganych)

    if (!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //sprawdzamy email
    {
        $output = json_encode(array('type' => 'error', 'text' => 'Niepoprawny adres e-mail. Wiadomość nie została wysłana.'));
        die($output);
    }
    if (strlen($user_Message) <= 5) //Sprawdzamy czy wiadomość ma więcej niż 5 znaków
    {
        $output = json_encode(array('type' => 'error', 'text' => 'Treść wiadomości musi być dłuższa niż 5 znaków. Wiadomość nie została wysłana.'));
        die($output);
    }

    //Nagłówki do Maila
    $headers = 'From: ' . $user_Email . '' . "\r\n" .
        'Name: ' . $user_Name . '' . "\r\n" .
        'Form type: ' . $form_type . '' . "\r\n" .
        'Reply-To: ' . $user_Email . '' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $sentMail = @mail($to_Email, $subject, $user_Message . ' -' . $headers);

    if (!$sentMail) {
        $output = json_encode(array('type' => 'error', 'text' => 'Nie można wysłać wiadomości. Błąd serwera.'));
        die($output);
    } else {
        $output = json_encode(array('type' => 'message', 'text' => 'Dziękuję za wysłanie wiadomości.'));
        die($output);
    }
}
?>