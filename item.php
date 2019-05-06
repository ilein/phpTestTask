<?php
class Member {
	private $id;
	private $name;
	private $avg;
	private $cntReplace;

	function Member($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	// функции
	private function CntAvg() {
		$nameTpl = "/^" . $this->id . '[-][0-9]{3}.txt/';
		$path = 'texts';
		$conts = scandir($path);
		
		$i = 0;
		$numsym = 0;
		foreach ($conts as $node) {
			if (preg_match($nameTpl, $node)) {
				$file = file_get_contents($path . '/' . $node);
				$numsym = $numsym + strlen($file);
				$i++;
			}
		}
		
		if ($i !== 0) {
			$this->avg = $numsym/$i;
		}
		else 
		{
			$this->avg = 0;
		}
	}
	
	private function ReplaceDate() {
		$nameTpl = "/^" . $this->id . '[-][0-9]{3}.txt/';
		$dataTpl = '/(\d{2})\/(\d{2})\/(\d{2})/';  //dd/mm/yy
		$path = 'texts';
		$newPath = 'output_texts';
		$conts = scandir($path);
		
		$i = 0;
		$cnt = 0;
		$cntReplace = 0;
		foreach ($conts as $node) {
			if (preg_match($nameTpl, $node)) {
				copy($path . '/' . $node, $newPath . '/' . $node);
				$file = file_get_contents($newPath . '/' . $node);
				$file = preg_replace_callback($dataTpl, 
					function ($matches) {
							$date = DateTime::createFromFormat('d/m/y', $matches[0]);
							return date_format($date, 'm-d-Y');
						},
					$file, -1, $cnt);
				$fp = fopen($newPath . '/' . $node, "w+"); // перезаписываем
				fwrite($fp,$file);
				fclose($fp);
				
				$i++;
				$cntReplace = $cntReplace + $cnt;
			}
		}
		$this->cntReplace = $cntReplace;
	}
	
	function PrintAvg() {
		$this->CntAvg();
		echo $this->name . " - " . $this->avg . "\n";
	}
	
	function PrintReplaceCnt() {
		$this->ReplaceDate();
		echo $this->name . " - " . $this->cntReplace . "\n";
	}
	
};

?>