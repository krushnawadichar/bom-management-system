<?php

namespace App\Repositories\Interfaces;

interface BomRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function find($id);
    public function findAll(array $filters = []);
    public function findByBomNumber($bomNumber);
    public function getLineItems($bomHeaderId);
    public function updateInventoryStatus($bomHeaderId);
}