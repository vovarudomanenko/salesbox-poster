<?php

namespace App\Poster\Events;

class PosterWebhookEvent {
    protected $params;
    protected $object;
    protected $object_id;
    protected $action;
    protected $account;
    protected $verify;
    protected $data;
    public function __construct($params) {
        $this->params = $params;
        $this->object = $params['object'] ?? null;
        $this->object_id = $params['object_id'] ?? null;
        $this->action = $params['action'] ?? null;
        $this->account = $params['account'] ?? null;
        $this->verify = $params['verify'] ?? null;
        $this->data = $params['data'] ?? null;
    }

    public function getParams() {
        return $this->params;
    }

    public function getObjectId() {
        return $this->object_id;
    }

    public function getAccount() {
        return $this->account;
    }

    public function getData() {
        return $this->data;
    }

    public function getVerify() {
        return $this->verify;
    }

    public function getObject() {
        return $this->object;
    }

    public function getAction() {
        return $this->action;
    }

    public function isAdded(): bool {
        return $this->getAction() === 'added';
    }

    public function isChanged(): bool {
        return $this->getAction() === 'changed';
    }

    public function isRestored(): bool {
        return $this->getAction() === 'restored';
    }

    public function isRemoved(): bool {
        return $this->getAction() === 'removed';
    }
}
