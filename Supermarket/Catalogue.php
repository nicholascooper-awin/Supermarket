<?php

class Catalogue {

    public function isAllowed(Book $book): bool
    {
        return in_array($book->getIsbn(),[
            '8726782638726',
            '8726782638727',
        ]);
    }
}