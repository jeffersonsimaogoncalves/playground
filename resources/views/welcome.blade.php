@php use App\Util\CertificateDomain; @endphp
@php
    $domains = [
     'sekolahlinux.com',
                        '127.0.0.1',
                        'localhost',
                        'laravel.com',
                        'filamentphp.com',
                        'github.com',
                        'globo.com',
    ];
    $data = [ ];
    foreach ($domains as $domain){
        $data[$domain] = CertificateDomain::getDomain($domain);
    }
        dd($data);
@endphp
