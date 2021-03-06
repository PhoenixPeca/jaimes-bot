# Wordset Dictionary

This is an optimized version of the Wordset Dictionary.

https://github.com/wordset/wordset-dictionary

---

*Optimization was achieved using the code below:*
> **WARNING**:
    Use this code only when necessary. Exposing
    this code to the public web may pose
    significant risks. Consider
    yourself warned.
````php
<?php
/*
WARNING:
    Use this code only when necessary. Exposing
    this code to the public web may pose
    significant risks. Consider
    yourself warned.
*/
set_time_limit(300);
foreach (glob("*.json") as $file) {
    if (file_exists($file)){
		$dictionary = file_get_contents($file);
		$datasets = json_decode($dictionary);
		foreach ($datasets as $word_entry=>$word_properties) {
			$traverse = function () use ($word_properties) {
				unset($word_properties->wordset_id);
				unset($word_properties->editors);
				unset($word_properties->contributors);
				foreach ($word_properties as $word_property=>$value) {
					$traverse = function () use ($value) {
						if (is_array($value)) {
							foreach ($value as $meaning=>$item) {
								unset($item->id);
								$data[] = $item; 
							}
							return $data;
						} else {
							return $value;
						}
					};
					$data->$word_property = $traverse();
				}
				return $data;
			};
			$data->$word_entry = $traverse();
		}
		unlink($file);
		file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
		unset($data);
		echo "$file\n";
	}
}
````
