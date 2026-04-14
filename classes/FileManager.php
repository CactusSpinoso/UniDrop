<?php
class FileManager
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . "/../classes/database.php";
        $this->db = new Database();
    }

    public function getUserFiles(int $idUser): array
    {
        $result = $this->db->query(
            "SELECT f.ID, f.NomeOriginale, f.SHA1, f.DataCaricamento
             FROM files f
             INNER JOIN user_files uf ON uf.ID_File = f.ID
             WHERE uf.ID_User = $idUser
             ORDER BY f.DataCaricamento DESC"
        );

        $files = [];
        while ($row = $result->fetch_assoc()) {
            $files[] = $row;
        }
        return $files;
    }
}
