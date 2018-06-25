<?php

namespace Adr\Domain\Repositories;

use Adr\Domain\Models\Post;

class PostRepository
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * MerchantPortalService constructor.
     * @param \PDO $db
     * @param Merchant $postModel
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function all()
    {

        $q = $this->db->prepare('
            SELECT * FROM posts
        ');

        $q->execute();

        if (!$q->rowCount()) {
            return [];
        }

        $posts = $q->fetchAll();

        $post_models = [];

        foreach ($posts as $row) {
            $post_models[] = (new Post)
                ->setId($row->id)
                ->setTitle($row->title);
        }

        return array_map(function ($post) {
            return (array) [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
            ];
        }, $post_models);
    }

    public function create(array $data)
    {
        $q = $this->db->prepare('
            INSERT INTO posts (id, title) VALUES (:p_id, :p_title)
        ');

        $q->execute([
            'p_id' => $data['id'],
            'p_title' => $data['title'],
        ]);

        return [
            'id' => $data['id'],
            'title' => $data['title'],
        ];
    }
}
