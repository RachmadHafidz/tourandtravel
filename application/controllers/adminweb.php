<?php

class adminweb extends CI_Controller {
	//menampilkan model admin 
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
	}
	//menampilkan tampilan login admin 
	public function index() {
		$data['logo'] = $this->admin_model->GetLogo();
		$this->load->view('adminweb/login',$data);
	}
	//menampilkan fungsi validasi login admin 
	public function login() {

		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if ($this->form_validation->run()==FALSE) {

		$data['logo'] = $this->admin_model->GetLogo();
		$this->load->view('adminweb/login',$data);

		}
		else {

			$username= $this->input->post('username');
			$password = md5($this->input->post('password'));

			$this->admin_model->CekAdminLogin($username,$password);

		}
	}
	//menampilkan fungsi session admin setelah login
	public function home() {

		if($this->session->userdata("logged_in")!=="") {
			$this->template->load('template','adminweb/home');
		}
		else{
			redirect('adminweb');

		}
	}
	//menampilkan fungsi logout setelah keluar dari halaman admin
	public function logout() {
		$this->session->sess_destroy();
		redirect("adminweb");
	} 


	//Awal Admin
	public function admin() {
		if($this->session->userdata("logged_in")!=="") {

			$data['data_admin'] = $this->admin_model->Getadmin();
			$this->template->load('template','adminweb/admin/index',$data);
		}
		else {
			redirect("adminweb");
		}
	} 
	
	//fungsi menghapus Admin 
	public function admin_delete() {
		$id = $this->uri->segment(3);
		$this->admin_model->Deleteadmin($id);

		$this->session->set_flashdata('message','Admin Berhasil Dihapus');
		redirect('adminweb/admin');
	}
	
	//fungsi menambah Admin 
	public function admin_tambah() {
		$this->template->load('template','adminweb/admin/add');
	}

	public function admin_simpan() {

			$this->form_validation->set_rules('nama_admin', 'Nama Admin', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('hak_akses', 'Hak Akses', 'required');
			
			

			if ($this->form_validation->run() == FALSE)
			{
				$this->template->load('template','adminweb/admin/add');
			}
			else {

						$in_data['nama_admin'] 		= $this->input->post('nama_admin');
						$in_data['email'] 			= $this->input->post('email');
						$in_data['username'] 		= $this->input->post('username');
						$in_data['password'] 		= md5($this->input->post('password'));
						$in_data['phone'] 			= $this->input->post('phone');
						$in_data['hak_akses'] 		= $this->input->post('hak_akses');
						$this->db->insert("tbl_admin",$in_data);

					$this->session->set_flashdata('berhasil','Admin Berhasil Disimpan');
					redirect("adminweb/admin");
				
			}

	}
	//fungsi mengedit Admin 
	public function admin_edit() {
		$id = $this->uri->segment(3);
		$query = $this->admin_model->GetadminEdit($id);
		foreach ($query->result_array() as $tampil) {
			$data['id_admin'] = $tampil['id_admin'];
			$data['nama_admin'] = $tampil['nama_admin'];
			$data['email'] = $tampil['email'];
			$data['username'] = $tampil['username'];
			$data['password'] = $tampil['password'];
			$data['phone'] = $tampil['phone'];
			$data['hak_akses'] = $tampil['hak_akses'];
			
		}
		$this->template->load('template','adminweb/admin/edit',$data);
	}
	//fungsi mengupdate Admin 
	public function admin_update() {
		$id['id_admin'] = $this->input->post("id_admin");

		if ($this->input->post('password')!=="") {

			$in_data['nama_admin'] = $this->input->post('nama_admin');
			$in_data['email'] = $this->input->post('email');
			$in_data['username'] = $this->input->post('username');
			$in_data['password'] = md5($this->input->post('password'));
			$in_data['phone'] = $this->input->post('phone');
			$in_data['hak_akses'] = $this->input->post('hak_akses');

		}
		else {
			$in_data['nama_admin'] = $this->input->post('nama_admin');
			$in_data['email'] = $this->input->post('email');
			$in_data['username'] = $this->input->post('username');
			$in_data['phone'] = $this->input->post('phone');
			$in_data['hak_akses'] = $this->input->post('hak_akses');
		}
								
		$this->db->update("tbl_admin",$in_data,$id);

		$this->session->set_flashdata('update','Admin Berhasil Diupdate');
		redirect("adminweb/admin");
		
	}
	//Akhir Admin

	

	//Awal Produk 
	public function produk () {

		$data['data_produk'] = $this->admin_model->GetProduk();
		$this->template->load('template','adminweb/produk/index',$data);
	}
	//Akhir Produk
	//fungsi menambah Produk 
	public function produk_tambah(){
		$data['kode_produk'] = $this->admin_model->GetMaxKodeProduk();
		$data['data_brand'] = $this->admin_model->GetBrand();
		$data['data_kategori'] = $this->admin_model->GetKategori();
		$this->template->load('template','adminweb/produk/add',$data);
	}
	//fungsi menyimpan Produk
	public function produk_simpan() {
		$this->form_validation->set_rules('nama_produk','Nama Produk','required');
		$this->form_validation->set_rules('brand_id','Brand','required');
		$this->form_validation->set_rules('kategori_id','Kategori','required');
		$this->form_validation->set_rules('harga','Harga','required');
		$this->form_validation->set_rules('stok','Stok','required');
		$this->form_validation->set_rules('deskripsi','Deskripsi','required');

		if ($this->form_validation->run()==FALSE) {

			$data['kode_produk'] = $this->admin_model->GetMaxKodeProduk();
			$data['data_brand'] = $this->admin_model->GetBrand();
			$data['data_kategori'] = $this->admin_model->GetKategori();
			$this->template->load('template','adminweb/produk/add',$data);

		}
		else {

			if(empty($_FILES['userfile']['name']))
				{
					
						$in_data['kode_produk'] = $this->input->post('kode_produk');
						$in_data['nama_produk'] = $this->input->post('nama_produk');
						$in_data['harga'] = $this->input->post('harga');
						$in_data['stok'] = $this->input->post('stok');
						$in_data['deskripsi'] = $this->input->post('deskripsi');
						$in_data['kategori_id'] = $this->input->post('kategori_id');
						$in_data['brand_id'] = $this->input->post('brand_id');
						$this->db->insert("tbl_produk",$in_data);

					$this->session->set_flashdata('berhasil','Produk Berhasil Disimpan');
					redirect("adminweb/produk");
				}
				else
				{
					$config['upload_path'] = './images/produk/';
					$config['allowed_types']= 'gif|jpg|png|jpeg|jfif';
					$config['encrypt_name']	= TRUE;
					$config['remove_spaces']	= TRUE;	
					$config['max_size']     = '10000000000000000000000';
					$config['max_width']  	= '10000000000000000000000';
					$config['max_height']  	= '10000000000000000000000';
					
			 
					$this->load->library('upload', $config);
	 
					if ($this->upload->do_upload("userfile")) {
						$data	 	= $this->upload->data();
			 
						/* PATH */
						$source             = "./images/produk/".$data['file_name'] ;
						$destination_thumb	= "./images/produk/thumb/" ;
						$destination_medium	= "./images/produk/medium/" ;
						// Permission Configuration
						chmod($source, 0777) ;
			 
						/* Resizing Processing */
						// Configuration Of Image Manipulation :: Static
						$this->load->library('image_lib') ;
						$img['image_library'] = 'GD2';
						$img['create_thumb']  = TRUE;
						$img['maintain_ratio']= TRUE;
			 
						/// Limit Width Resize
						$limit_medium   = 268 ;
						$limit_thumb    = 249 ;
			 
						// Size Image Limit was using (LIMIT TOP)
						$limit_use  = $data['image_width'] > $data['image_height'] ? $data['image_width'] : $data['image_height'] ;
			 
						// Percentase Resize
						if ($limit_use > $limit_thumb) {
							$percent_medium = $limit_medium/$limit_use ;
							$percent_thumb  = $limit_thumb/$limit_use ;
						}
			 
						//// Making THUMBNAIL ///////
						$img['width']  = $limit_use > $limit_thumb ?  $data['image_width'] * $percent_thumb : $data['image_width'] ;
						$img['height'] = $limit_use > $limit_thumb ?  $data['image_height'] * $percent_thumb : $data['image_height'] ;
			 
						// Configuration Of Image Manipulation :: Dynamic
						$img['thumb_marker'] = '';
						$img['quality']      = '100%' ;
						$img['source_image'] = $source ;
						$img['new_image']    = $destination_thumb ;
			 
						// Do Resizing
						$this->image_lib->initialize($img);
						$this->image_lib->resize();
						$this->image_lib->clear() ;

						$img['width']   = $limit_use > $limit_medium ?  $data['image_width'] * $percent_medium : $data['image_width'] ;
						$img['height']  = $limit_use > $limit_medium ?  $data['image_height'] * $percent_medium : $data['image_height'] ;
			 
			 			// Configuration Of Image Manipulation :: Dynamic
						$img['thumb_marker'] = '';
						$img['quality']      = '100%' ;
						$img['source_image'] = $source ;
						$img['new_image']    = $destination_medium ;
			 
						// Do Resizing
						$this->image_lib->initialize($img);
						$this->image_lib->resize();
						$this->image_lib->clear() ;
						
						$in_data['kode_produk'] = $this->input->post('kode_produk');
						$in_data['nama_produk'] = $this->input->post('nama_produk');
						$in_data['harga'] = $this->input->post('harga');
						$in_data['stok'] = $this->input->post('stok');
						$in_data['deskripsi'] = $this->input->post('deskripsi');
						$in_data['kategori_id'] = $this->input->post('kategori_id');
						$in_data['brand_id'] = $this->input->post('brand_id');
						$in_data['gambar'] = $data['file_name'];
						
						
						
						$this->db->insert("tbl_produk",$in_data);

						

				
						
						$this->session->set_flashdata('berhasil','Produk Berhasil Disimpan');
						redirect("adminweb/produk");
						
					}
					else 
					{
						$this->template->load('template','adminweb/produk/error');
					}
				}

		}
	}
	//fungsi menghapus Produk
	public function produk_delete() {
		$id_produk = $this->uri->segment(3);
		$this->admin_model->DeleteProduk($id_produk);

		$this->session->set_flashdata('message','Produk Berhasil Dihapus');
		redirect('adminweb/produk');

	}

	//Awal Logo
	public function logo () {
		$query = $this->admin_model->GetLogo();
		foreach ($query->result_array() as $tampil) {
			$data['id_logo']=$tampil['id_logo'];
			$data['gambar']=$tampil['gambar'];
		}
		$this->template->load('template','adminweb/logo/index',$data);
	}

	 public function logo_simpan()
   {
		if($this->session->userdata("logged_in")!=="") {
			$id['id_logo'] = $this->input->post("id_logo");
			$id_logo = $this->input->post("id_logo");
			

				if(empty($_FILES['userfile']['name']))
				{
					
					
					$this->session->set_flashdata('message','Logo Berhasil Diupdate');
					redirect("adminweb/logo");
				}
				else
				{
					$config['upload_path'] = './images/logo/';
					$config['allowed_types']= 'gif|jpg|png|jpeg';
					$config['encrypt_name']	= TRUE;
					$config['remove_spaces']	= TRUE;	
					$config['max_size']     = '3000';
					$config['max_width']  	= '500';
					$config['max_height']  	= '250';
			 
					$this->load->library('upload', $config);
	 
					if ($this->upload->do_upload("userfile")) {
						$data	 	= $this->upload->data();
			 
						/* PATH */
						$source             = "./images/logo/".$data['file_name'] ;
						$destination_thumb	= "./images/logo/thumb/" ;
			 
						// Permission Configuration
						chmod($source, 0777) ;
			 
						/* Resizing Processing */
						// Configuration Of Image Manipulation :: Static
						$this->load->library('image_lib') ;
						$img['image_library'] = 'GD2';
						$img['create_thumb']  = TRUE;
						$img['maintain_ratio']= TRUE;
			 
						/// Limit Width Resize
						$limit_thumb    = 640 ;
			 
						// Size Image Limit was using (LIMIT TOP)
						$limit_use  = $data['image_width'] > $data['image_height'] ? $data['image_width'] : $data['image_height'] ;
			 
						// Percentase Resize
						if ($limit_use > $limit_thumb) {
							$percent_thumb  = $limit_thumb/$limit_use ;
						}
			 
						//// Making THUMBNAIL ///////
						$img['width']  = $limit_use > $limit_thumb ?  $data['image_width'] * $percent_thumb : $data['image_width'] ;
						$img['height'] = $limit_use > $limit_thumb ?  $data['image_height'] * $percent_thumb : $data['image_height'] ;
			 
						// Configuration Of Image Manipulation :: Dynamic
						$img['thumb_marker'] = '';
						$img['quality']      = '100%' ;
						$img['source_image'] = $source ;
						$img['new_image']    = $destination_thumb ;
			 
						// Do Resizing
						$this->image_lib->initialize($img);
						$this->image_lib->resize();
						$this->image_lib->clear() ;
						
						$in_data['gambar'] = $data['file_name'];
						$this->db->update("tbl_logo",$in_data,$id);
				
						
						$this->session->set_flashdata('message','Logo Berhasil Diupdate');
						redirect("adminweb/logo");
						
					}
					else 
					{
						echo $this->upload->display_errors('<p>','</p>');
					}
				}
			
		}
		else
		{
			redirect("adminweb");
		}
   }
	//Akhir Logo

	//fungsi mengedit Produk
	public function produk_edit() {
		$id_produk = $this->uri->segment(3);
		$query = $this->admin_model->EditProduk($id_produk);
		foreach ($query->result_array() as $tampil) {

			$data['id_produk']= $tampil['id_produk'];
			$data['kode_produk']= $tampil['kode_produk'];
			$data['nama_produk']= $tampil['nama_produk'];
			$data['harga']= $tampil['harga'];
			$data['stok']= $tampil['stok'];
			$data['deskripsi']= $tampil['deskripsi'];
			$data['kategori_id']= $tampil['kategori_id'];
			$data['brand_id']= $tampil['brand_id'];
			
		}
		$data['data_kategori'] = $this->admin_model->GetKategori();
		$data['data_brand']  = $this->admin_model->GetBrand();
		$this->template->load('template','adminweb/produk/edit',$data);
	}
	//fungsi mengupdate Produk
	public function produk_update() {
		$id['id_produk'] = $this->input->post("id_produk");

		if(empty($_FILES['userfile']['name']))
				{
					
						$in_data['kode_produk'] = $this->input->post('kode_produk');
						$in_data['nama_produk'] = $this->input->post('nama_produk');
						$in_data['harga'] = $this->input->post('harga');
						$in_data['stok'] = $this->input->post('stok');
						$in_data['deskripsi'] = $this->input->post('deskripsi');
						$in_data['kategori_id'] = $this->input->post('kategori_id');
						$in_data['brand_id'] = $this->input->post('brand_id');
					
						$this->db->update("tbl_produk",$in_data,$id);

					$this->session->set_flashdata('update','Produk Berhasil Diupdate');
					redirect("adminweb/produk");
				}
				else
				{
					$config['upload_path'] = './images/produk/';
					$config['allowed_types']= 'gif|jpg|png|jpeg|jfif';
					$config['encrypt_name']	= TRUE;
					$config['remove_spaces']	= TRUE;	
					$config['max_size']     = '100000000000000000000000';
					$config['max_width']  	= '100000000000000000000000';
					$config['max_height']  	= '100000000000000000000000';
					
			 
					$this->load->library('upload', $config);
	 
					if ($this->upload->do_upload("userfile")) {
						$data	 	= $this->upload->data();
			 
						/* PATH */
						$source             = "./images/produk/".$data['file_name'] ;
						$destination_thumb	= "./images/produk/thumb/" ;
						$destination_medium	= "./images/produk/medium/" ;
			 
						// Permission Configuration
						chmod($source, 0777) ;
			 
						/* Resizing Processing */
						// Configuration Of Image Manipulation :: Static
						$this->load->library('image_lib') ;
						$img['image_library'] = 'GD2';
						$img['create_thumb']  = TRUE;
						$img['maintain_ratio']= TRUE;
			 
						/// Limit Width Resize
						$limit_medium   = 268 ;
						$limit_thumb    = 249 ;
			 
						// Size Image Limit was using (LIMIT TOP)
						$limit_use  = $data['image_width'] > $data['image_height'] ? $data['image_width'] : $data['image_height'] ;
			 
						// Percentase Resize
						if ($limit_use > $limit_thumb) {
							$percent_medium = $limit_medium/$limit_use ;
							$percent_thumb  = $limit_thumb/$limit_use ;
						}
			 
						//// Making THUMBNAIL ///////
						$img['width']  = $limit_use > $limit_thumb ?  $data['image_width'] * $percent_thumb : $data['image_width'] ;
						$img['height'] = $limit_use > $limit_thumb ?  $data['image_height'] * $percent_thumb : $data['image_height'] ;
			 
						// Configuration Of Image Manipulation :: Dynamic
						$img['thumb_marker'] = '';
						$img['quality']      = '100%' ;
						$img['source_image'] = $source ;
						$img['new_image']    = $destination_thumb ;
			 
						// Do Resizing
						$this->image_lib->initialize($img);
						$this->image_lib->resize();
						$this->image_lib->clear() ;
	 
						////// Making MEDIUM /////////////
						$img['width']   = $limit_use > $limit_medium ?  $data['image_width'] * $percent_medium : $data['image_width'] ;
						$img['height']  = $limit_use > $limit_medium ?  $data['image_height'] * $percent_medium : $data['image_height'] ;
			 
						// Configuration Of Image Manipulation :: Dynamic
						$img['thumb_marker'] = '';
						$img['quality']      = '100%' ;
						$img['source_image'] = $source ;
						$img['new_image']    = $destination_medium ;
			 
						// Do Resizing
						$this->image_lib->initialize($img);
						$this->image_lib->resize();
						$this->image_lib->clear() ;
						
						$in_data['kode_produk'] = $this->input->post('kode_produk');
						$in_data['nama_produk'] = $this->input->post('nama_produk');
						$in_data['harga'] = $this->input->post('harga');
						$in_data['stok'] = $this->input->post('stok');
						$in_data['deskripsi'] = $this->input->post('deskripsi');
						$in_data['kategori_id'] = $this->input->post('kategori_id');
						$in_data['brand_id'] = $this->input->post('brand_id');
						$in_data['gambar'] = $data['file_name'];
						
						$this->db->update("tbl_produk",$in_data,$id);
				
						
						$this->session->set_flashdata('update','Produk Berhasil Diupdate');
						redirect("adminweb/produk");
						
					}
					else 
					{
						$this->template->load('template','adminweb/produk/error');
					}
				}

	}

}