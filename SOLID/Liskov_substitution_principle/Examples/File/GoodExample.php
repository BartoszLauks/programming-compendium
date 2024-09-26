<?php

interface FileInterface
{
    public function read();
    public function write();
}

class ReadWriteFile implements FileInterface
{
    public function read()
    {
        // ...
    }

    public function write()
    {
        // ...
    }
}

class ReadOnlyFile implements FileInterface
{
    public function read()
    {
        // ...
    }

    public function write()
    {
        throw new ItsReadOnlyFileException();
    }
}
