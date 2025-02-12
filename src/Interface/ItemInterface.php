<?php
namespace wwaz\Favigation\Interface;

interface ItemInterface
{
    public function toArray(): array;

    public function setId(int | string $val): self;

    public function getId(): int | string;

    public function setParentId(int $val): self;

    public function getParentId(): int | string | null;

    public function setOrdering(int $val): self;

    public function getOrdering(): int | null;

    public function setTitle(mixed $val): self;

    public function getTitle(): mixed;

    public function setUrl(string $val): self;

    public function getUrl(): string | null;

    public function setTarget(string $val): self;

    public function getTarget(): string | null;

    public function setIcon(string $val): self;

    public function getIcon(): string | null;
}
