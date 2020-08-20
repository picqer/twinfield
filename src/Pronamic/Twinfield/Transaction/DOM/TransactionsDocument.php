<?php

namespace Pronamic\Twinfield\Transaction\DOM;

use Pronamic\Twinfield\Transaction\Transaction;

/**
 * TransactionsDocument class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionsDocument extends \DOMDocument
{
    /**
     * Holds the <transactions> element
     * that all additional elements should be a child of.
     *
     * @var \DOMElement
     */
    private $transactionsElement;

    /**
     * Creates the <transasctions> element and adds it to the property
     * transactionsElement.
     */
    public function __construct()
    {
        parent::__construct();

        $this->transactionsElement = $this->createElement('transactions');
        $this->appendChild($this->transactionsElement);
    }

    /**
     * Turns a passed Transaction class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the transaction
     * to this DOMDocument instance for submission usage.
     *
     * @param \Pronamic\Twinfield\Transaction\Transaction $transaction
     */
    public function addTransaction(Transaction $transaction)
    {
        // Transaction
        $transactionElement = $this->createElement('transaction');
        $transactionElement->setAttribute('destiny', $transaction->getDestiny());
        $this->transactionsElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $officeElement = $this->createElement('office', $transaction->getOffice());
        $codeElement = $this->createElement('code', $transaction->getCode());
        $dateElement = $this->createElement('date', $transaction->getDate());
        $invoiceNumberElement = $this->createElement('invoicenumber', $transaction->getInvoiceNumber());
        $freetext1Element = $this->createElement('freetext1', $transaction->getFreetext1());
        $freetext2Element = $this->createElement('freetext2', $transaction->getFreetext2());
        $freetext3Element = $this->createElement('freetext3', $transaction->getFreetext3());

        if ($transaction->getDueDate() !== null) {
             $dueDateElement = $this->createElement('duedate', $transaction->getDueDate());
             $headerElement->appendChild($dueDateElement);
        }



        $headerElement->appendChild($officeElement);
        $headerElement->appendChild($codeElement);
        $headerElement->appendChild($dateElement);

        $headerElement->appendChild($invoiceNumberElement);
        $headerElement->appendChild($freetext1Element);
        $headerElement->appendChild($freetext2Element);
        $headerElement->appendChild($freetext3Element);

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $lineElement->setAttribute('id', $transactionLine->getID());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $dim2Element = $this->createElement('dim2', $transactionLine->getDim2());

            $value = $transactionLine->getValue();
            //$value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);

            if ($transactionLine->getVatTotal() !== null && $transactionLine->getType() == 'total') {
                $vattotalElement = $this->createElement('vattotal', $transactionLine->getVatTotal());
                $lineElement->appendChild($vattotalElement);
            }

            if ($transactionLine->getType() != 'total') {
                if ($transactionLine->getVatCode() !== null) {
                    $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
                }
            }

            $descriptionNode = $this->createTextNode($transactionLine->getDescription());
            $descriptionElement = $this->createElement('description');
            $descriptionElement->appendChild($descriptionNode);

            $debitCreditNode = $this->createTextNode($transactionLine->getDebitCredit() );
            $debitCreditElement = $this->createElement('debitcredit');
            $debitCreditElement->appendChild($debitCreditNode);
            $lineElement->appendChild($debitCreditElement);


            $lineElement->appendChild($dim1Element);
            $lineElement->appendChild($dim2Element);
            $lineElement->appendChild($valueElement);

            $performanceType = $transactionLine->getPerformanceType();
            if (!empty($performanceType)) {
                $perfElement = $this->createElement('performancetype', $performanceType);
                $lineElement->appendChild($perfElement);
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createElement('vatvalue', $vatValue);
                $lineElement->appendChild($vatElement);
            }

            if ($transactionLine->getType() != 'total') {
                if ($transactionLine->getVatCode() !== null) {
                     $lineElement->appendChild($vatCodeElement);
                }

            }

            $lineElement->appendChild($descriptionElement);


            if ($transactionLine->getMatchesNumber()) {


                $matchesElement = $this->createElement('matches');
                $matchesSetElement = $this->createElement('set');

                $matchesDateElement = $this->createElement('matchdate', $transactionLine->getMatchesDate());
                $matchesLinesElement = $this->createElement('lines');
                $matchesLineElement = $this->createElement('line');

                $matchesCodeElement = $this->createElement('code',$transactionLine->getMatchesCode());
                $matchesNumberElement = $this->createElement('number',$transactionLine->getMatchesNumber());
                $matchesSublineElement = $this->createElement('line',$transactionLine->getMatchesLine());
                $matchesMethodElement = $this->createElement('method',$transactionLine->getMatchesMethod());
                $matchesMatchvalueElement = $this->createElement('matchvalue',$transactionLine->getMatchesMatchvalue());


                $matchesLineElement->appendChild($matchesCodeElement);
                $matchesLineElement->appendChild($matchesNumberElement);
                $matchesLineElement->appendChild($matchesSublineElement);
                $matchesLineElement->appendChild($matchesMethodElement);
                $matchesLineElement->appendChild($matchesMatchvalueElement);



                $matchesLinesElement->appendChild($matchesLineElement);
                $matchesSetElement->appendChild($matchesLinesElement);
                $matchesSetElement->appendChild($matchesDateElement);
                $matchesElement->appendChild($matchesSetElement);
                $lineElement->appendChild($matchesElement);



            }



        }
    }
}

