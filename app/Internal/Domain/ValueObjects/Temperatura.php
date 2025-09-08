<?php

namespace App\Internal\Domain\ValueObjects;

/**
 * Value Object para Temperatura
 * Encapsula regras específicas de temperatura de torra de café
 */
class Temperatura
{
    // Constantes de negócio
    public const TEMPERATURA_MINIMA = -50.0;
    public const TEMPERATURA_MAXIMA = 500.0;
    public const TEMPERATURA_CRITICA = 240.0;
    public const TEMPERATURA_SEGURA_MIN = 160.0;
    public const TEMPERATURA_PRE_AQUECIMENTO = 100.0;
    public const TEMPERATURA_DESENVOLVIMENTO = 150.0;
    public const TEMPERATURA_PRIMEIRO_CRACK = 196.0;
    public const TEMPERATURA_SEGUNDO_CRACK = 224.0;
    public const TEMPERATURA_FINALIZACAO = 230.0;

    public function __construct(
        private readonly float $value
    ) {
        if (!$this->isValidRange()) {
            throw new \InvalidArgumentException(
                "Temperatura {$this->value}°C está fora do range válido"
            );
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return number_format($this->value, 1) . '°C';
    }

    // === VALIDAÇÕES ===

    public function isValid(): bool
    {
        return $this->isValidRange();
    }

    private function isValidRange(): bool
    {
        return $this->value >= self::TEMPERATURA_MINIMA &&
               $this->value <= self::TEMPERATURA_MAXIMA;
    }

    // === REGRAS DE NEGÓCIO ===

    public function isCritica(): bool
    {
        return $this->value > self::TEMPERATURA_CRITICA;
    }

    public function isSegura(): bool
    {
        return $this->value >= self::TEMPERATURA_SEGURA_MIN &&
               $this->value <= self::TEMPERATURA_CRITICA;
    }

    public function isPreAquecimento(): bool
    {
        return $this->value < self::TEMPERATURA_PRE_AQUECIMENTO;
    }

    public function isDesenvolvimento(): bool
    {
        return $this->value >= self::TEMPERATURA_DESENVOLVIMENTO &&
               $this->value < self::TEMPERATURA_PRIMEIRO_CRACK;
    }

    public function isPrimeiroCrack(): bool
    {
        return $this->value >= self::TEMPERATURA_PRIMEIRO_CRACK &&
               $this->value < self::TEMPERATURA_SEGUNDO_CRACK;
    }

    public function isSegundoCrack(): bool
    {
        return $this->value >= self::TEMPERATURA_SEGUNDO_CRACK &&
               $this->value < self::TEMPERATURA_FINALIZACAO;
    }

    public function isFinalizacao(): bool
    {
        return $this->value >= self::TEMPERATURA_FINALIZACAO;
    }

    public function getEstado(): string
    {
        return match (true) {
            $this->isPreAquecimento() => 'pre_aquecimento',
            $this->isDesenvolvimento() => 'desenvolvimento',
            $this->isPrimeiroCrack() => 'primeiro_crack',
            $this->isSegundoCrack() => 'segundo_crack',
            $this->isFinalizacao() => 'finalizacao',
            default => 'indefinido'
        };
    }

    public function getCorRecomendada(): string
    {
        return match (true) {
            $this->value < 180 => '#3498db', // Azul - frio
            $this->value < 200 => '#f39c12', // Laranja - aquecendo
            $this->value < 220 => '#e67e22', // Laranja escuro - desenvolvimento
            $this->value < 240 => '#e74c3c', // Vermelho - crítico
            default => '#c0392b' // Vermelho escuro - perigoso
        };
    }

    // === MÉTODOS ESTÁTICOS ===

    public static function fromCelsius(float $celsius): self
    {
        return new self($celsius);
    }

    public static function fromFahrenheit(float $fahrenheit): self
    {
        $celsius = ($fahrenheit - 32) * 5 / 9;
        return new self($celsius);
    }

    public function toFahrenheit(): float
    {
        return ($this->value * 9 / 5) + 32;
    }

    // === COMPARAÇÕES ===

    public function equals(Temperatura $other): bool
    {
        return abs($this->value - $other->value) < 0.1;
    }

    public function isGreaterThan(Temperatura $other): bool
    {
        return $this->value > $other->value;
    }

    public function isLessThan(Temperatura $other): bool
    {
        return $this->value < $other->value;
    }
}
