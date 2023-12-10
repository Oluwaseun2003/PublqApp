<?php

namespace Kreativdev\Installer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KdInstallerController extends Controller
{
    public function index() {
        return view('installer::form');
    }

    public function addInstaller(Request $request) {
        $sql = $request->file('database_file');
        $allowedExts = array('sql');

        $rules = [
            'database_file' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $sql, $allowedExts) {
                    if ($request->hasFile('database_file')) {
                        $ext = $sql->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only sql file is allowed");
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);

        // moving database.sql under public/installer folder
        @unlink(__DIR__ . '/../../files/public/installer/database.sql');
        $request->file('database_file')->move(__DIR__ . '/../../files/public/installer', 'database.sql');

        // @touch(base_path('vendor/mockery/mockery/verified'));
        // @touch(base_path('storage/installed'));
        @unlink(base_path('vendor/mockery/mockery/verified'));
        @unlink(base_path('storage/installed'));
        
        // putting AppServiceProvider contents to mockery.php & adding installer redirecion code in AppServiceProvider.php
        $this->copyContentsOfFiles(base_path('app/Providers/AppServiceProvider.php'), base_path('vendor/league/flysystem/mockery.php'));
        $this->copyContentsOfFiles(__DIR__ . '/../../files/AppServiceProvider.php', base_path('app/Providers/AppServiceProvider.php'));


        // putting web.php contents to machie.php
        $this->copyContentsOfFiles(base_path('routes/web.php'), base_path('vendor/league/flysystem/machie.php'));


        // replacing public/installer folder
        $this->delete_directory(base_path('public/installer'));
        $this->recurse_copy(__DIR__ . '/../../files/public/installer', base_path('public/installer'));


        // adding config/installer.php
        $this->copyContentsOfFiles(__DIR__ . '/../../files/config/installer.php', base_path('config/installer.php'));


        // adding resources/lang/en/installer_messages.php
        $this->copyContentsOfFiles(__DIR__ . '/../../files/lang/installer_messages.php', base_path('resources/lang/en/installer_messages.php'));


        // replacing resources/views/vendor/installer folder
        $this->delete_directory(base_path('resources/views/vendor/installer'));
        $this->recurse_copy(__DIR__ . '/../../files/views/vendor/installer', base_path('resources/views/vendor/installer'));


        // vendor/rachidlaasri
        $this->delete_directory(base_path('vendor/rachidlaasri'));
        $this->recurse_copy(__DIR__ . '/../../files/vendor/rachidlaasri', base_path('vendor/rachidlaasri'));


        // adding version.json
        $this->createVersionJson();
    }

    public function createVersionJson() {

        $data = [
            "version" => config('installer.version'),
            "released_on" => config('installer.released_on')
        ];

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        if ($jsonData !== false) {
            if (file_put_contents(base_path("version.json"), $jsonData) !== false) {
                echo "JSON file created successfully.";
            } else {
                echo "Error creating the JSON file.";
            }
        } else {
            echo "Error encoding the data to JSON.";
        }
    }

    function delete_directory($dirname)
    {
        $dir_handle = false;
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    $this->delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function copyContentsOfFiles($src, $des) {
        $sourceFile = $src; // Specify the name and extension of the source file
        $destinationFile = $des; // Specify the name and extension of the destination file
        
        // Create the destination file
        if (file_put_contents($destinationFile, "") !== false) {
            // Read the contents from the source file
            $content = file_get_contents($sourceFile);
        
            if ($content !== false) {
                // Write the contents to the destination file
                if (file_put_contents($destinationFile, $content) !== false) {
                    echo "File created and contents copied successfully.";
                } else {
                    echo "Error copying the contents to the destination file.";
                }
            } else {
                echo "Error reading the source file.";
            }
        } else {
            echo "Error creating the destination file.";
        }
        
    }
}
