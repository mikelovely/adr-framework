<?php

namespace Adr\Domain\Services;

use Adr\Domain\Messages\ValidationMessage;
use Adr\Domain\Repositories\PostRepository;
use Valitron\Validator;

class PostStoreService
{
    protected $posts;

    protected $validator;

    public function __construct(PostRepository $posts, Validator $validator)
    {
        $this->posts = $posts;
        $this->validator = $validator;
    }

    public function handle(array $data)
    {
        $validator = $this->validator->withData($data);

        $validator->mapFieldsRules($this->rules());

        if (!$validator->validate()) {
            return new ValidationMessage($validator->errors());
        }

        return $this->posts->create($data);
    }

    protected function rules()
    {
        return [
            'title' => ['required']
        ];
    }
}
