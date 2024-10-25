<?php
class database
{	private $host="192.168.56.2";	
	private $dbname="serviceserver";	
	private $conn;

	// koneksi ke database mysql
	private $driver="mysql";
	private $user="root";
	private $password="rootpassword";
	private $port="3306";
	
	/*
	// koneksi ke database postgresql
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/
	
	// function yang pertama kali di-load saat class dipanggil
	public function __construct()
	{	// koneksi database
		try
		{	if ($this->driver == 'mysql')
			{	$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",$this->user,$this->password);	
			} elseif ($this->driver == 'pgsql')
			{	$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");	
			}	
		} catch (PDOException $e)
		{	echo "Koneksi gagal";			
		}
	}	

	public function tampil_semua_data()
	{	$query = $this->conn->prepare("select nim, nama, alamat, hp from mahasiswa order by nim");
		$query->execute();	
		// mengambil banyak data dengan fetchAll
		$data = $query->fetchAll(PDO::FETCH_ASSOC);		
		// mengembalikan data	
		return $data;	
		// hapus variable dari memory	
		$query->closeCursor();
		unset($data);
		// atau dapat menggunakan
		// $query = null;		
		// $data = null;	
	}

	public function tampil_data($nim)
	{	$query = $this->conn->prepare("select nim,nama,alamat,hp from mahasiswa where nim=?");
		$query->execute(array($nim));	
		// mengambil satu data dengan fetch	
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($nim,$data);
	}
	
	public function tambah_data($data)
	{	$query = $this->conn->prepare("insert ignore into mahasiswa (nim,nama,alamat,hp) values (?,?,?,?)");
		$query->execute(array($data['nim'],$data['nama'],$data['alamat'],$data['hp']));	
		$query->closeCursor(); 
		unset($data);
	}

	public function ubah_data($data)
	{	$query = $this->conn->prepare("update mahasiswa set nama=?,alamat=?,hp=? where nim=?");
		$query->execute(array($data['nama'],$data['alamat'],$data['hp'],$data['nim']));	
		$query->closeCursor(); 
		unset($data);
	}

	public function hapus_data($nim)
	{	$query = $this->conn->prepare("delete from mahasiswa where nim=?");
		$query->execute(array($nim));	
		$query->closeCursor(); 
		unset($nim);
	}
}
?>
