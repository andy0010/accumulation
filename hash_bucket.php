
<?php
header("Content-Type:text/html; charset=utf-8");
class HashMap{
	private  $map;

	function __construct($capacity) {

		$this->map = [
			'count' => $capacity,
			'buckets' => []
		];
   	}

	/**
	* 哈希计算方法
	*/
	private function my_hash($key){
		$hash = 5381;
		$str_arr = str_split($key);

		array_walk($str_arr, function($v) use(&$hash){
			$hash = (($hash << 5) + $hash) + $v;
		});

		return $hash;
	}

	/**
	 * 把元素放进哈希桶
	 */
	public function put($pair){
		
		if($this->map == null || $pair['key'] == null || $pair['value'] == null){
			return 0;
		}
		//获取$pair路由到那个桶
		$index = $this->my_hash($pair['key']) % $this->map['count'];
		$bucket = &$this->map['buckets'][$index];

		//这里采用顺序存放解决哈希冲突
		if( !isset($bucket['count']) ||  $bucket['count'] == 0){
			$bucket['count'] = 0;
			$bucket['pairs'] = [];
		}
		$bucket['count'] = $bucket['count'] + 1;
		$bucket['pairs'][] = $pair;
		return 1;
	}	

	/**
	 * 在桶中根据key获取元素
	 */
	private function get_pair($bucket, $key){
		if($bucket['count'] == 0 || $key == null){
			return null;
		}
		$pair_arr = $bucket['pairs'];
		$pair = [];
		foreach ($pair_arr as  $v) {
			if($v['key'] == $key){
				$pair = $v;
				return $pair;
			}
		}
		return null;
	}


	/**
	 * 根据key获取元素
	 * 返回pair
	 */
	public function get($key){
		if($this->map == null || $key== null ){
			return null;
		}

		$index = $this->my_hash($key) % $this->map['count'];

		$bucket = $this->map['buckets'][$index];

		echo "in hashmap function get, the conflict_bucket:".'<br>';
		print_r($bucket);

		$pair =	$this->get_pair($bucket, $key);
		
		return $pair;
	}

	/**
	 * 获取哈希表元素个数
	 */
	public function get_count(){
		$count = 0;
		$bucket_arr = $this->map['buckets'];
		array_walk($bucket_arr, function($bucket) use (&$count){

			array_walk($bucket['pairs'], function($pair)use (&$count){
				$count++;
			});

		});
		return $count;
	}

	/**
	 * 获取冲突的桶
	 */
	public function get_conflict_bucket(){
		$bucket_arr = $this->map['buckets'];
		$conflict_bucket_arr = [];

		array_walk($bucket_arr, function($bucket, $index) use (&$conflict_bucket_arr){
			
			if(sizeof($bucket['pairs']) > 1){
				$conflict_bucket_arr[$index] = $bucket;
			}
		});
		return $conflict_bucket_arr;
	}
}


$hashmap = new HashMap(95);
$name_arr = ['寒冰射手', '玛西亚之力 盖伦', '塔里克', '机械公敌', '皮城女警', '阿木木', '布里茨', '大发明家', '卡牌大师'];

for($i=0; $i < 108; $i++){
	$index = rand(0, 8);
	$pair = ['key' => $i, 'value' => $name_arr[$index]];
	$hashmap->put($pair);
}

$conflict_bucket_arr = $hashmap->get_conflict_bucket();

$index = key($conflict_bucket_arr);
echo 'conflict_index: '.$index.'<br>';
echo "<pre>";
$key_0 = $conflict_bucket_arr[$index]['pairs'][0]['key'];
$pair = $hashmap->get($key_0);
echo "the pair whith the key is " . $key_0.' is :<br>';
print_r($pair);
echo "<br>";

$key_1 = $conflict_bucket_arr[$index]['pairs'][1]['key'];
$pair = $hashmap->get($key_1);
echo "the pair whith the key is " . $key_1.' is :<br>';
print_r($pair);










 	
 
		

		
