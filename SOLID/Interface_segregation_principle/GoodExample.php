<?php

interface ExportablePdf
{
    public function getPDF();
}

interface ExportableCSV
{
    public function getCSV();
}

class Invoice implements ExportablePdf, ExportableCSV
{
    public function getPDF() {
        //
    }
    public function getCSV() {
        //
    }
}

class CreditNote implements ExportableCSV
{
    public function getCSV() {
        //
    }
}