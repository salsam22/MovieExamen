<?php

namespace App\Controller;

use App\Exceptions\FileUploadException;
use App\Exceptions\NoUploadedFileException;
use App\FlashMessage;
use App\Mapper\MovieMapper;
use App\Movie;
use App\Repository\MovieRepository;
use App\Registry;
use App\Response;
use App\UploadedFileHandler;

class MovieController
{
    const MAX_SIZE = 1024 * 1000;
    private MovieRepository $movieRepository;

    public function __construct()
    {
        $mapper = new MovieMapper();
        $this->movieRepository = new MovieRepository($mapper);
    }

    public function list(): Response
    {
        $message = FlashMessage::get("info");

        $movies = $this->movieRepository->findAll();

        $logger = Registry::get(Registry::LOGGER);
        $logger->info("s'ha executat una consulta");

        $response = new Response();
        $response->setView("index");
        $response->setLayout("default");
        $response->setData(compact('movies', 'message'));

        return $response;
        //require __DIR__ . "/../../views/index.view.php";
    }

    public function show(int $id): Response {
        $movie = $this->movieRepository->find($id);

        $response = new Response();
        $response->setView("movie");
        $response->setLayout("default");
        $response->setData(compact('movie'));

        return $response;
    }

    public function create(): Response {

        if (isPost()) {
            die("Aquest pàgina sols admet el mètode GET");
        }

        $data = FlashMessage::get("data", []);

        if (empty($data)) {
            $data["title"] = "";
            $data["release_date"] = "";
            $data["overview"] = "";
            $data["poster"] = "";
            $data["rating"] = 0;
        }

        $errors = FlashMessage::get("errors", []);

        $formToken =  bin2hex(random_bytes(16));

        FlashMessage::set("token", $formToken);

        $response = new Response();
        $response->setView("movies-create");
        $response->setLayout("default");
        $response->setData(compact('errors','formToken', 'data'));

        return $response;
    }

    public function delete(int $id)
    {
        $movie = $this->movieRepository->find($id);
        $data = $movie->toArray();
        if (empty($data)) {
            $data["title"] = "";
            $data["release_date"] = "";
            $data["overview"] = "";
            $data["poster"] = "";
            $data["rating"] = 0;
        }
        if (isPost()) {

            $idTemp = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
            $response = filter_input(INPUT_POST, "response", FILTER_SANITIZE_SPECIAL_CHARS);


            if ($response!=="Sí")
                $errors[] = "L'esborrat ha sigut cancelat per l'usuari";

            if (!empty($idTemp))
                $id = $idTemp;
            else
                throw  new Exception("Invalid ID");

        }


        $movie = Movie::fromArray($data);
        $this->movieRepository->delete($id);
        $message = "S'ha actualitzat el registre amb l'ID ({$movie->getId()})";

        $response = new Response();
        $response->setView("movies-delete");
        $response->setLayout("default");
        $response->setData(compact('movie', 'data', 'message'));
        return $response;
    }

    public function edit(int $id): Response
    {
        //die("editant la pel·licula $id");

        // $id = $_POST["id"]?? $_GET["id"] ?? null;

        //if (empty($id))
        //    throw new Exception("Id Invalid");
        //else
        //    $id = (int)$id;

        $message = "";
        $movie = $this->movieRepository->find($id);
        $data = $movie->toArray();

        //var_dump($data);
        if (empty($data))
            throw new \Exception("La pel·lícula seleccionada no existeix");


        $validTypes = ["image/jpeg", "image/jpg"];

        $errors = [];

        // per a la vista necessitem saber si s'ha processat el formulari
        if (isPost()) {
            $data["title"] = clean($_POST["title"]);
            $data["overview"] = clean($_POST["overview"]);
            $data["release_date"] = $_POST["release_date"];
            try {
                $uploadedFileHandler = new UploadedFileHandler("poster", ["image/jpeg"], self::MAX_SIZE);
                $data["poster"] = $uploadedFileHandler->handle("posters");

            } catch (NoUploadedFileException $e) {
                // no faig res perquè és una opció vàlida en UPDATE.
            } catch (FileUploadException $e) {
                $errors[] = $e->getMessage();
            }

            if (empty($errors)) {
                try {
                    $movie = Movie::fromArray($data);
                    $this->movieRepository->save($movie);
                    $message = "S'ha actualitzat el registre amb l'ID ({$movie->getId()})";
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

            }
        }
        $response = new Response();
        $response->setView("movies-edit");
        $response->setLayout("default");
        $response->setData(compact('movie', 'data', 'message'));

        return $response;

        //require __DIR__ ."/../../views/movies-edit.view.php";
    }

    public function store() {
        if (!isPost()) {
            die("Aquesta pàgina sols usa el mètode POST");
        }
        $validTypes = ["image/jpeg", "image/jpg"];
        $errors = [];
        if (empty($data)) {
            $data["title"] = "";
            $data["release_date"] = "";
            $data["overview"] = "";
            $data["poster"] = "";
            $data["rating"] = 0;
        }
        $movieRepository = $this->movieRepository;
// per a la vista necessitem saber si s'ha processat el formulari
        $token = FlashMessage::get("token");

        if (empty($_POST["title"])) {
            $errors[] = "El titulo de la pelicula es necesario";
        } else {
            $data["title"] = clean($_POST["title"]??"");
        }
        if (empty($_POST["overview"])) {
            $errors[] = "El overview de la pelicula es necesario";
        } else {
            $data["overview"] = clean($_POST["overview"]??"");
        }
        if (empty($_POST["release_date"])) {
            $errors[] = "El release_date de la pelicula es necesario";
        } else {
            $data["release_date"] = $_POST["release_date"];;

        }
        if (empty($_POST["rating"])) {
            $errors[] = "El rating de la pelicula es necesario";
        } else {
            $data["rating"] = $_POST["rating"];
        }
        try {
            $uploadedFileHandler = new UploadedFileHandler("poster", ["image/jpeg"], self::MAX_SIZE);
            $data["poster"] = $uploadedFileHandler->handle("posters");

        } catch (FileUploadException $e) {
            $errors[] = $e->getMessage();
        }
        if (empty($data)) {
            $errors[] = "Tienes que llenar todos los campos.";
        }
        try {
            $movie = Movie::fromArray($data);
        }
        catch (\Webmozart\Assert\InvalidArgumentException $e) {
            $errors[]= $e->getMessage();
        }

        if (empty($errors)) {

            $movie = Movie::fromArray($data);
            $movieRepository->save($movie);
            $flash = new FlashMessage();
            $flash->set("info", "S'ha creat correctament la pelicula amb l'ID {$movie->getId()}");
            header("Location: /");
            exit();
        }
// com que si hi ha hagut èxit redirigirem a la pàgina principal plantegem ací el pitjor escenari.
        FlashMessage::set("data", $data);
        FlashMessage::set("errors", $errors);

        $response = new Response();
        $response->setView("movies-create");
        $response->setLayout("default");
        $response->setData(compact( 'data', 'errors'));
        return $response;
    }

}