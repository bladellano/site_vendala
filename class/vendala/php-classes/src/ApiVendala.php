<?php 

namespace Sts;

class ApiVendala{

	private static $url_base = "http://127.0.0.1:8000/api/";

	/**
	 * [getProducts recupera informações dos produtos na api.]
	 * @param  integer $pagination [Verifica se retorna com paginção ou não]
	 * @param  integer $page       [No da página solicitada]
	 * @return [array]             [Relação de produtos]
	 */
	public function getProducts($pagination, $page=1)
	{

		try {

			$header = [
            "Content-Type: application/json",
            "Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
            ];

			$ch = curl_init(self::$url_base."products?page={$page}&pagination={$pagination}");
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$r = curl_exec($ch);
			$aProducts = (json_decode($r,true));

			curl_close($ch);

			return $aProducts;

		} catch (Exception $e) {
			return json_encode([
				'status' => 'error',
				'msg' => $e->getMessage()
			]);
		}
	}

	public function getCategories()
	{
		try {
			$header = [
				"Content-Type: application/json",
				"Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
			];
			$ch = curl_init(self::$url_base."categories");
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$r = curl_exec($ch);
			$aCategories = (json_decode($r,true));

			curl_close($ch);

			return $aCategories;

		} catch (Exception $e) {
			return json_encode([
				'status' => 'error',
				'msg' => $e->getMessage()
			]);
		}

	}

	public function getLoginWithToken($fields,$header=[])
	{
		set_time_limit(60);

		try {

			$ch = curl_init(self::$url_base."auth/login");

			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

			curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$r = curl_exec($ch);

			$response = (json_decode($r,true));

			if($response['error']!="Unauthorized")
				return $response;	
			return [];

			curl_close($ch);
			
		} catch (Exception $e) {
			return json_encode([
				'status' => 'error',
				'msg' => $e->getMessage()
			]);
		}

	}

	public function logout()
	{

		$header = [
			"Content-Type: application/json",
			"Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
		];

		$ch = curl_init(self::$url_base."auth/logout");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$r = curl_exec($ch);
		$result = (json_decode($r,true));
	}

	public function verifyLogin()
	{
		$header = [
			"Content-Type: application/json",
			"Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
		];

		$ch = curl_init(self::$url_base."auth/me");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$r = curl_exec($ch);
		$result = (json_decode($r,true));

		if((int)$result['id'] == 0){
			header("Location:/login");
			exit;
		}
	}

	public function deleteProduct($id)
	{

		$ch = curl_init(self::$url_base."products/{$id}");

		$header = [
				"Content-Type: application/json",
				"Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
			];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;	

	}

	public function saveProduct($data)
	{
	
		$header = [
			"Authorization: Bearer " . ($_SESSION['User']['access_token'])?:""
		];

		$ch = curl_init();
		$url = self::$url_base."products";
		$postData = $data['dataForm'];

		foreach ($data['dataFile']['images']['tmp_name'] as $index => $file) {
			$postData['images[' . $index . ']'] = curl_file_create(
				realpath($file),
				mime_content_type($file),
				basename($file)
			);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

}