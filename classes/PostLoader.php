<?php


class PostLoader
{
    private array $allPosts = [];


    public function setAllPosts(string $fileName): void
    {
        foreach ($this->readFile($fileName) as $temp) {
            $this->allPosts[] = $temp;
        }
    }

    public function getAllPosts(): array
    {
        return $this->allPosts;
    }

    public function readFile(string $fileName): ?array
    {
        if (!file_get_contents($fileName)) {
            return null;
        }
        try {
            return json_decode(file_get_contents($fileName), true, 512, JSON_THROW_ON_ERROR);

        } catch (JsonException $e) {
            if ($e) {
                var_dump($e);
            }
            return null;
        }
    }

    public function writeToFile(string $fileName, Post $post): void
    {
        if (!file_get_contents($fileName)) {
            $toAdd[] = $post->toArray();
            try {
                file_put_contents($fileName, json_encode($toAdd, JSON_THROW_ON_ERROR));
            } catch (JsonException $e) {
                if ($e) {
                    var_dump($e);
                }
                return;
            }
        } else {
            try {

                //$temp = array  --> for each over temp
                //$temp = $this->readFile($fileName);
                //var_dump($temp);

                $toAdd[] = $post->toArray();
                foreach ($this->readFile($fileName) as $temp) {
                    $toAdd[] = $temp;
                }
                file_put_contents($fileName, json_encode($toAdd, JSON_THROW_ON_ERROR));

            } catch (JsonException $e) {
                if ($e) {
                    var_dump($e);
                }
                return;
            }
        }
    }
    //  PostLoader->writeToFile($filename,$post_15)
    // in case of no FILE_APPEND flag--> read file -> save to temp -> add to temp ($temp.$newinput) -> temp into file (overwrites)


}