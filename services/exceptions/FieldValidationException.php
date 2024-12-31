<?php

namespace services\exceptions;

class FieldValidationException extends \Exception
{
    /**
     * @var array Tableau des erreurs de validation
     */
    private $erreurs;

    /**
     * Constructeur de l'exception
     *
     * @param array $erreurs Tableau des erreurs de validation
     * @param int $code Code de l'exception (par défaut : 0)
     * @param \Exception|null $precedente Exception précédente pour le chaînage (facultatif)
     */
    public function __construct(array $erreurs, $code = 0, \Exception $precedente = null)
    {
        $this->erreurs = $erreurs;

        // Construit un message par défaut basé sur les erreurs
        $message = "La validation des champs a échoué avec les erreurs suivantes :";
        parent::__construct($message, $code, $precedente);
    }

    /**
     * Retourne le tableau des erreurs de validation
     *
     * @return array
     */
    public function getErreurs(): array
    {
        return $this->erreurs;
    }

    /**
     * Retourne un message formaté avec toutes les erreurs
     *
     * @return string
     */
    public function getErreursFormatees(): string
    {
        $formatees = "";
        foreach ($this->erreurs as $champ => $erreur) {
            $formatees .= "Champ '$champ' : $erreur\n";
        }
        return trim($formatees);
    }
}
