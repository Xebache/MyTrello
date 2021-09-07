<?php

trait DueDateTrait {

    private ?DateTime $dueDate;

    public function get_dueDate(): ?DateTime {
        return $this->dueDate;
    }

    public function get_dueDate_sql_format() {
        return $this->dueDate != null ? $this->dueDate->format('Y-m-d H:i:s') : null;
    }

    public function get_dueDate_format(): string {
        return $this->dueDate != null ? $this->dueDate->format('d/m/Y') : "";
    }

    public function get_dueDate_html_format(): string {
        return $this->dueDate != null ? $this->dueDate->format('Y-m-d') : "";
    }

    public function get_actual_date(): string {
        return (new DateTime())->format('Y-m-d');
    }

    public function set_dueDate(DateTime $dueDate) {
        $this->dueDate = $dueDate;
    }

    public function has_dueDate(): bool {
        return !is_null($this->get_dueDate());
    }

    public function is_due(): bool {
        if ($this->has_dueDate()) {
            return $this->get_dueDate() < new Datetime();
        }
        return false;
    }

    public function due_card(): string {
        if($this->is_due()) {
            return "due_card";
        }
        return "";
    }

}