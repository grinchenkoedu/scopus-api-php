<?php

namespace Scopus\Response;

interface IAbstract
{
    /**
     * @return IAuthor[]
     */
    public function getAuthors(): array;
}