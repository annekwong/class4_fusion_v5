<?php
class FtpComponent extends Object
{
	var $error = false;
	var $errors = array();
	var $conn = false;
	var $pasv = true;
	
	/**
	 * 	Connects to the given ftp server
	 * 	@param string server name
	 *	@return boolean Success
	 *	@access private
	 */
	function __connect($server)
	{
		$this->conn = ftp_connect($server);
		if ($this->conn)
		{
			return true;
		}
		else
		{
			$this->error = true;
			$this->errors[] = "Connecting to the server failed";
			return false;
		}
	}
	
	/**
	 * 	Connects and logs in to the given server with given credentials
	 *	@param string ftp server name
	 *	@param string ftp user name
	 *	@param string ftp user's password
	 *	@return boolean Success
	 *	@access public
	 */
	
	function login($server, $username, $password)
	{
		if (!$this->__connect($server))
		{
			return false;
		}
		$result = @ftp_login($this->conn, $username, $password);
		if (!$result)
		{
			$this->error = true;
			$this->errors[] = "FTP login failed";
			return false;
		}
		else
		{
			ftp_pasv($this->conn, $this->pasv);
			return true;
		}
	}
	
	/**
	 * 	Uploads single file if the connection is already established
	 *	with no existing directory checks - if path is not writable it fails
	 *	@param string local file path
	 * 	@param string remote file path
	 *	@return boolean Success
	 *	@access public
	 */
	
	function upload($source, $destination)
	{
		if (!$this->conn)
		{
			$this->error = true;
			$this->errors[] = "You need to establish connection before upload. Use login()";
			return false;
		}
		
		if (file_exists($source))
		{			
			$result = @ftp_put($this->conn, $destination, $source, FTP_BINARY);
			if ($result)
			{
				return true;
			}
			else
			{
				$this->error = true;
				$this->errors[] = "File upload failed";
				return false;
			}
		}
		else
		{
			$this->error = true;
			$this->errors[] = "Your supplied source file does not exist on server. Check your path";
			return false;
		}
	}
	
	/**
	 *	Tries to change dir on remote FTP server
	 *	@param string remote directory to change dir to
	 *	@return boolean Success
	 *	@access public
	 */
	function cd($path)
	{
		if (!$this->conn)
		{
			$this->error = true;
			$this->errors[] = "You need to establish connection before changing directory. User login()";
			return false;
		}
		if (@ftp_chdir($this->conn, $path))
		{
			return true;
		}
		else
		{
			$this->error = true;
			$this->errors[] = "Couldn't change directory on server to: ".$path;
			return false;
		}
	}
	
	/**
	 * 	Changes permissions on single file/directory located on the remote FTP
	 *	@param string remote file/directory path
	 * 	@param octal permissions mode to change to
	 *	@return boolean Success
	 *	@access public
	 */
	function chmod($path, $mode=0755)
	{
		if (!$this->conn)
		{
			$this->error = true;
			$this->errors[] = "You need to establish connection before changing directory. User login()";
			return false;
		}
		if (ftp_chmod($this->conn, $mode, $path))
		{
			return true;
		}
		else
		{
			$this->error = true;
			$this->errors[] = "Couldn't chmod directory ". $path ." on server to mode: ". $mode;
			return false;
		}
	}
	
	/**
	 *	Changes permissions on given remote file/directory recursively
	 * 	@param string remote file path
	 *	@param octal permission representation in octal to be changed to
	 *	@return boolean Success
	 *	@access public
	 */
	function chmodR($path, $mod=0755)
	{
		if (!$this->conn)
		{
			$this->error = true;
			$this->errors[] = "You need to establish connection before changing directory. User login()";
			return false;
		}
		$items = ftp_nlist($this->conn, $path);		
		foreach($items as $item)
		{
			$name = basename($item);
			if ($name != "." && $name != "..")
			{
				if ($this->cd($item))
				{					
					$this->chmodR($item, $mod);					
				}
				else
				{
					$this->chmod($item, $mod);
				}
			}
		}
		$this->chmod($path, $mod);		
		return true;
	}
	
	/**
	 *	Closes established connection
	 *	@return boolean Success
	 *	@access public
	 */
	function close()
	{
		return ftp_close($this->conn);
	}
	
	/**
	 *	Alias for create
	 * 	@param string remote file path
	 *	@return boolean Success
	 *	@access public
	 */
	function mkdir($path)
	{
		return $this->create($path);
	}
	
	/**
	 * 	Recursively creates directories all needed directories on remote server
	 * 	@param string remote directory path
	 *	@return boolean Success
	 *	@access public
	 */
	function create($path)
	{
		if (!$this->conn)
		{
			$this->error = true;
			$this->errors[] = "You need to establish connection before creating directory. Use login()";
			return false;
		}
		
		if ($this->cd($path))
		{
			return true;
		}
		
		$nextPathname = substr($path, 0, strrpos($path, '/'));
		if ($this->create($nextPathname))
		{
			if(!$this->cd($path))
			{
				$result = @ftp_mkdir($this->conn, $path);
				if ($result)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	 * 	Recursively uploads whole local directory to remote server
	 * 	@param string local directory path
	 *	@param string remote directory path - where to upload
	 *	@param string path to strip from local files when uploading - to make uploading to the relative path
	 *	@return boolean Success
	 *	@access public
	 */
	function upload_dir($source, $destination, $relative_path="", $verbose = false)
	{
		//$files = $this->__getFileList($source, array());
		uses('Folder');
		$folder = new Folder($source);
		$files = $folder->findRecursive();
		$result = array();
		$result['total_files'] = count($files);
		if ($verbose)
		{
			echo "Is viso failų: ". $result['total_files']."<br />";
		}
		$count = 0;
		foreach($files as $file)
		{
			$remote_path = dirname(str_replace($relative_path, '', $file)).'/';
			$filename = basename($file);
			$dest = $destination. $remote_path . $filename;
			if ($this->cd($destination.$remote_path))
			{
				if ($this->upload($file, $dest))
				{
					$count++;
					$result['files']['uploaded'][] = $file;
					if ($verbose)
					{
						echo ". ";
						ob_flush();
						flush();
					}
				}
				else
				{
					$result['files']['failed'][] = $file;
					if ($verbose)
					{
						echo "- ";
						ob_flush();
						flush();
					}
				}
			}
			else
			{
				if ($this->mkdir($destination.$remote_path))
				{
					if ($this->upload($file, $dest))
					{
						$count++;
						$result['files']['uploaded'][] = $file;
						if ($verbose)
						{
							echo ". ";
							ob_flush();
							flush();
						}
					}
					else
					{
						$result['files']['failed'][] = $file;
						if ($verbose)
						{
							echo "- ";
							ob_flush();
							flush();
						}
					}
				}
				else
				{
					$result['files']['failed'][] = $file;
					if ($verbose)
					{
						echo "- ";
						ob_flush();
						flush();
					}
				}
			}
			//$this->upload($file, $dest);
		}
		if ($verbose)
		{
			echo "<br />";
		}
		$result['uploaded_files'] = $count;
		return $result;
	}
	
}
?>