<?php namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Request;

trait UploadFileTrait
{
    public function uploadFile($fileFieldName, $destinationPath, $params = null)
    {
        $lang = $params['lang'] ?? 'en';

        if (!Request::hasFile($fileFieldName)) {
            return [
                'success' => false,
                'code'    => 1,
                'error'   => $lang === 'en' ? 'File was not uploaded.' : 'El archivo no se pudo subir.',
            ];
        }

        $file = Request::file($fileFieldName);

        if (!$file->isValid()) {
            return [
                'success' => false,
                'code'    => 2,
                'error'   => $lang === 'en' ? 'Uploaded file is not valid.' : 'El archivo subido no es válido.',
            ];
        }

        if (!empty($params['allowed_extensions'])) {
            try {
                $fileExtension = strtolower(ltrim($file->extension(), '.'));

                if (
                    (
                        is_array($params['allowed_extensions'])
                        && !in_array($fileExtension, $params['allowed_extensions'])
                    ) || (
                        is_string($params['allowed_extensions'])
                        && !str_contains($params['allowed_extensions'], $fileExtension)
                    )
                ) {
                    return [
                        'success' => false,
                        'code'    => 5,
                        'error'   => $lang === 'en' ? 'File extension could not be determined.' : 'No se pudo determinar la extensión del archivo.',
                    ];
                }
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'code'    => 6,
                    'error'   => $lang === 'en' ? 'File extension not allowed.' : 'Extensión del archivo no válida.',
                ];
            }
        }

        try {
            $fileSize = $file->getSize();

            if (!empty($params['maxSize']) && $fileSize > (integer)$params['maxSize']) {        // maxSize in bytes
                return [
                    'success'  => false,
                    'code'     => 3,
                    'maxSize'  => (integer)$params['maxSize'],
                    'fileSize' => $fileSize,
                    'error'    => $lang === 'en' ? 'File exceeds size limit.' : 'El tamaño del archivo es mayor del permitido.',
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'code'    => 5,
                'error'   => $lang === 'en' ? 'File size could not be determined.' : 'No se pudo determinar el tamaño del archivo.',
            ];
        }

        $fileName = $file->getClientOriginalName();

        if (!empty($params['unique_name'])) {
            $nameInfo = pathinfo($fileName);
            $ranStr = substr(sha1(time()), 0, 6);
            $fileName = $nameInfo['filename'] . '-' . $ranStr . '.' . $nameInfo['extension'];
        }

        $prefix = !empty($params['prefix']) ? $params['prefix'].'_' : '';

        $fileName = $this->cleanFileName($prefix . $fileName);

        try {
            $destinationPath = rtrim(public_path() . '/' . $destinationPath, '/') . '/';
            $file->move($destinationPath, $fileName);
            if (!empty($params['oldFileName']) && file_exists($destinationPath . $params['oldFileName'])) {
                unlink($destinationPath . $params['oldFileName']);
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'code'    => 4,
                'error'   => $lang === 'en' ? 'File could not be uploaded.' : 'No se pudo subir el archivo.',
            ];
        }

        return [
            'success'  => true,
            'code'     => 0,
            'file'     => $file,
            'fileName' => $fileName,
            'fileSize' => $fileSize,
        ];
    }

    public function cleanFileName($fileName)
    {
        $patterns = ['/[^a-zA-z0-9_\-\.\s]/', '/\s+/', '/\-+/'];
        $replacements = ['', '-', '-'];
        return strtolower(preg_replace($patterns , $replacements, $fileName));
    }

}
