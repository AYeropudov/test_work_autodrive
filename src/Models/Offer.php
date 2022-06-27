<?php

namespace Models;

use InvalidArgumentException;
use JsonSerializable;
use Parsers\ParserXml;
use Throwable;
use ValueError;

class Offer implements JsonSerializable, IModel
{
    private int $id;
    private string $mark='';
    private string $model='';
    private string $generation="";
    private int $year;
    private int $run;
    private string $color='';
    private string $bodyType='';
    private string $engineType='';
    private string $transmission='';
    private string $gearType='';
    private int $generationId=0;
    private array $castErrors=[];


    /**
     * @param string $type
     * @param object| array $obj
     */
    public function __construct(string $type, $obj)
    {
        if ($type === ParserXml::class){
            $data = $this->castFromXmlElement($obj);
            foreach ($data as $key=>$value){
                $this->$key($value);
            }
        }
        elseif ($type === 'array'){
            $data = $this->castFromArray($obj);
            foreach ($data as $key=>$value){
                $this->$key($value);
            }
        }
        else {
                throw new InvalidArgumentException("Unknown parser. Can't cast obj in `Offer` with type: `$type`");
        }

    }

    /**
     * @param object $obj
     * @return array
     */
    private function castFromXmlElement(object $obj): array
    {
        $objValues = get_object_vars($obj);
        $availableKeys = array_keys($objValues);
        $data = [];
        foreach ($availableKeys as $key) {
            try {
                $data[$this->getPropertiesMap()[$key]] = mb_convert_encoding($objValues[$key], "UTF-8");
            } catch (Throwable $e) {
                $this->castErrors[] = "Property: `$key` isn't available in `Offer` obj";
            }
        }
        return $data;
    }

    /**
     * @param array $obj
     * @return array
     */
    private function castFromArray(array $obj): array
    {
        $availableKeys = array_keys($obj);
        $data = [];
        foreach ($availableKeys as $key) {
            try {
                if($obj[$key]) {
                    $data[$this->getPropertiesMap()[$key]] = mb_convert_encoding($obj[$key], "UTF-8");
                }
            } catch (Throwable $e) {
                $this->castErrors[] = "Property: `$key` isn't available in `Offer` obj";
            }
        }
        return $data;
    }
    private function getPropertiesMap(): array
    {
        return [
            "id" => "setId",
            "mark" => "setMark",
            "model" => "setModel",
            "generation" => "setGeneration",
            "year" => "setYear",
            "run" => "setRun",
            "color" => "setColor",
            "body-type" => "setBodyType",
            "body_type" => "setBodyType",
            "engine-type" => "setEngineType",
            "engine_type" => "setEngineType",
            "transmission" => "setTransmission",
            "gear-type" => "setGearType",
            "gear_type" => "setGearType",
            "generation_id" => "setGenerationId"
        ];
    }

    public function asArray(): array
    {
        $tmp = [
            "id" =>            $this->id,
            "mark" =>          $this->mark,
            "model" =>         $this->model,
            "generation" =>    $this->generation,
            "year" =>          $this->year,
            "run" =>           $this->run,
            "color" =>         $this->color,
            "body-type" =>     $this->bodyType,
            "engine-type" =>   $this->engineType,
            "transmission" =>  $this->transmission,
            "gear-type" =>     $this->gearType,
            "generation_id" => $this->generationId
        ];
        return ["type" => self::class, "values" => $tmp];
    }

    public function jsonSerialize()
    {
        return json_encode($this->asArray());
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = (int)$id;
    }

    /**
     * @param string $mark
     */
    public function setMark(string $mark): void
    {
        $this->mark = $mark;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @param string $generation
     */
    public function setGeneration(string $generation): void
    {
        $this->generation = $generation;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = (int)$year;
    }

    /**
     * @param mixed $run
     */
    public function setRun($run): void
    {
        $this->run = (int)$run;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @param string $bodyType
     */
    public function setBodyType(string $bodyType): void
    {
        $this->bodyType = $bodyType;
    }

    /**
     * @param string $engineType
     */
    public function setEngineType(string $engineType): void
    {
        $this->engineType = $engineType;
    }

    /**
     * @param string $transmission
     */
    public function setTransmission(string $transmission): void
    {
        $this->transmission = $transmission;
    }

    /**
     * @param string $gearType
     */
    public function setGearType(string $gearType): void
    {
        $this->gearType = $gearType;
    }

    /**
     * @param mixed $generationId
     */
    public function setGenerationId($generationId): void
    {
        $this->generationId = (int)$generationId;
    }

    /**
     * @param string $attrName
     * @return mixed
     * @throws ValueError
     */
    public function getAttr(string $attrName) {
        if(property_exists($this, $attrName)){
            return $this->$attrName;
        }
        throw new ValueError("An error happened, due retrive Offer property `$attrName`");
    }

    public static function tblName(): string
    {
        return "offers";
    }

    /**
     * @return string
     */
    public function getInsertQuery(): string
    {
        $generationId = ($this->generationId==0)? 'null' : $this->generationId;
        return "($this->id, '$this->model', '$this->mark', $this->year, $this->run, '$this->color', '$this->bodyType', '$this->engineType','$this->gearType', '$this->transmission', $generationId)";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return strval($this->id);
    }

}