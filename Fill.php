<?php

    class Fill
    {
        //Block states
        protected const EMPT = 0; // Empty block
        protected const FULL = 1; // Full block
        protected const WALL = 2; // Wall block

        //Cases inside file
        protected $cases = [];

        //Format case to visual
        protected static function format($case)
        {
            for ($i = 0; $i < count($case); $i++) {
                $case[$i] = implode(' ', $case[$i]);
            }

            $case = "\n" . implode("\n", $case) . "\n";

            return $case;
        }

        //Result message formatter
        protected static function result($status, $msg = null)
        {
            return [
                'status' => $status,
                'msg'    => $msg . "\n",
            ];
        }

        //Get a block
        protected function block($board, $x, $y)
        {
            return $board[$y][$x];
        }

        //Leak a block to left or right
        protected function leak($board, $dir = 'L')
        {
            $w = sizeof($board[0]);
            $h = sizeof($board);

            for ($c = 0; $c < $w; $c++) {
                for ($l = 0; $l < $h; $l++) {
                    $b = ($dir == 'L') ? $this->block($board, $c, $l) : $this->block($board, $w - 1 - $c, $l);

                    if ($b != self::FULL && $b != self::WALL) {
                        continue 2;
                    }

                    $neighbor = $c ? $this->block($board, ($dir == 'L') ? $c - 1 : $w - $c, $l) : 0;

                    if ($b == self::FULL && (!$c || $neighbor == self::EMPT)) {
                        $board[$l][$dir == 'L' ? $c : $w - $c - 1] = self::EMPT;
                    }
                }
            }

            return $board;
        }

        //Load file
        public function loadFile(string $filePath)
        {
            //Sanity check: file exists?
            if (!file_exists($filePath)) {
                return self::result(false, 'Arquivo com dados não encontrado');
            }

            //Grab all data from file
            $data = file($filePath);

            //Sanity check: case count
            $caseCount = (int) array_shift($data);

            if (!is_int($caseCount) || count($data) != $caseCount * 2) {
                return self::result(false, 'Número de casos inválido');
            }

            //Parses all cases
            for ($n = 0; $n < $caseCount; $n++) {
                $size  = array_shift($data);
                $items = explode(' ', trim(array_shift($data)));

                //Sanity check: the (redundant!) count information matches the array length
                if ($size != count($items)) {
                    return self::result(false, printf('Erro na especificação do caso %u', $n + 1));
                }

                //Store the case
                $this->cases[] = $items;
            }

            return self::result(true);
        }

        //Get case count
        public function getNumCases()
        {
            return count($this->cases);
        }

        //Get a case
        public function getCase($caseIndex, $formatted = false)
        {
            //Check index viability
            if (!is_int($caseIndex) || $caseIndex < 0 || $caseIndex >= $this->getNumCases()) {
                return self::result(false, 'Índice de caso inválido');
            }

            $case   = $this->cases[$caseIndex];
            $height = max($case);
            $width  = count($case);

            $data = [];

            for ($row = 0; $row < $height; $row++) {
                $layer = [];
                for ($col = 0; $col < $width; $col++) {
                    $layer[] = ($row < $height - $case[$col]) ? self::FULL : self::WALL;
                }
                $data[] = $layer;
            }

            if ($formatted) {
                $data = self::format($data);
            }

            return [
                'status' => true,
                'width'  => $width,
                'height' => $height,
                'data'   => $data,
            ];
        }

        //Flood one case
        public function flood($caseIndex, $formatted = false)
        {
            if ($caseIndex >= count($this->cases)) {
                return self::result(false, 'Índice de caso inválido');
            }

            $board = $this->getCase($caseIndex)['data'];
            $board = $this->leak($board, 'L');
            $board = $this->leak($board, 'R');

            return [
                'status' => true,
                'water'  => substr_count(self::format($board), self::FULL),
                'data'   => $formatted ? self::format($board) : $board,
            ];
        }

        //Flood all cases
        public function floodAll()
        {
            for ($caseIndex = 0, $all = ''; $caseIndex < $this->getNumCases(); $caseIndex++) {
                $all .= $this->flood($caseIndex)['water'] . "\n";
            }

            return $all;
        }
    }
