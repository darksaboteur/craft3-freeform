<?php

namespace Solspace\Freeform\Library\Export\Objects;

use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\MultiDimensionalValueInterface;

class Row implements \IteratorAggregate
{
    /** @var Column[] */
    private $columns = [];

    /** @var bool */
    private $multiDimensionalFields = false;

    /** @var int */
    private $artificialRowCount = 0;

    /**
     * @param int $position
     *
     * @return Column|null
     */
    public function getColumn(int $position)
    {
        return $this->columns[$position] ?? null;
    }

    /**
     * @param Column $column
     *
     * @return Row
     */
    public function addColumn(Column $column): Row
    {
        $this->columns[] = $column;

        if ($column->getField() && $column->getField() instanceof MultiDimensionalValueInterface) {
            $this->multiDimensionalFields = true;

            $rowCount = count($column->getValue());
            if ($rowCount > 1) {
                $this->artificialRowCount = max($this->artificialRowCount, $rowCount - 1);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMultiDimensionalFields(): bool
    {
        return $this->multiDimensionalFields;
    }

    /**
     * @return int
     */
    public function getArtificialRowCount(): int
    {
        return $this->artificialRowCount;
    }

    /**
     * @return ColumnIterator
     */
    public function getIterator(): ColumnIterator
    {
        return new ColumnIterator($this);
    }
}
