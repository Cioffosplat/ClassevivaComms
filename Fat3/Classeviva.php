<?php

namespace Papaya\Classeviva\Students;

use AllowDynamicProperties;
use Exception;
use Papaya\Classeviva\ClassevivaEvent;

#[AllowDynamicProperties] class Classeviva
{
    private string $baseUrl = 'https://web.spaggiari.eu/rest/v1';

    public function Request($dir, $data = []): bool|string
    {
        if ($data == []) {
            curl_setopt($this->curl, CURLOPT_POST, false);
        } else {
            curl_setopt_array($this->curl, [
                CURLOPT_POSTFIELDS => $data,
            ]);
        }
        curl_setopt_array($this->curl, [
            CURLOPT_URL        => $this->baseUrl . $dir,
        ]);

        return curl_exec($this->curl);
    }

    public function postRequest($dir,$data): bool|string
    {
        curl_setopt_array($this->curl, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_URL        => $this->baseUrl . $dir,
        ]);

        return curl_exec($this->curl);
    }

    public function __construct($identity = null)
    {
        $this->ident = $identity;

        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_POST           => true,
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
                'ContentsDiary-Type: application/json',
                'User-Agent: CVVS/std/4.1.7 Android/10',
            ),
        ]);
    }

    public function login($username,$password): bool|string
    {
        $json = "{
            \"ident\":\"$this->ident\",
            \"pass\":\"$password\",
            \"uid\":\"$username\"
        }";
        $response = json_decode($this->Request('/auth/login', $json));

        if (!property_exists($response, 'error') && isset($response->token)) {
            $this->ident = $response->ident;
            $this->firstName = $response->firstName;
            $this->lastName = $response->lastName;
            $this->token = $response->token;
            $this->id = filter_var($response->ident, FILTER_SANITIZE_NUMBER_INT);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
                'User-Agent: CVVS/std/4.1.7 Android/10',
                'Z-Auth-Token: ' . $this->token,
            ));
        } elseif (isset($response->error)) {
            throw new Exception($response->error . PHP_EOL, 2);
        } else throw new Exception("Unknown error", 2);
        return $this->Request("/auth/login", $json);
    }

    public function avatar(): bool|string
    {
        return $this->Request("/auth/avatar");
    }

    public function sid(): bool|string
    {
        return $this->Request("/auth/_zsid");
    }

    public function status(): bool|string
    {
        return $this->Request('/auth/status');
    }

    public function ticket(): bool|string
    {
        return $this->Request('/auth/ticket');
    }

    public function options($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/_options");
    }


    public function absences($id, $token,$begin = null, $end = null): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        if ($begin != null) {
            if ($end != null) {
                return $this->Request("/students/$id/absences/details/$begin/$end");
            } else {
                return $this->Request("/students/$id/absences/details/$begin");
            }
        } else {
            return $this->Request("/students/$id/absences/details");
        }
    }

    public function agenda($id, $token,$begin, $end, $events = 'all'): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/agenda/$events/$begin/$end");
    }

    public function calendar($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/calendar/all");
    }

    public function card($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/card");
    }

    public function cards($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/cards");
    }

    public function didactics($id,$token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        if ($id != null) {
            return $this->Request("/students/$id/didactics/item/$id");
        } else {
            return $this->Request("/students/$id/didactics");
        }
    }

    public function documents($id, $token,$hash = null, bool $check = false): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        if ($hash != null) {
            if ($check) {
                return $this->Request("/students/$id/documents/check/$hash");
            } else {
                return $this->Request("/students/$id/documents/read/$hash");
            }
        } else {
            return $this->Request("/students/$id/documents");
        }
    }

    public function grades($id,$token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/grades");
    }

    public function lessons($id, $token,$start = null, $end = null): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        if ($start != null) {
            if ($end != null) {
                return $this->Request("/students/$id/lessons/$start/$end");
            } else {
                return $this->Request("/students/$id/lessons/$start");
            }
        } else {
            return $this->Request("/students/$id/lessons/today");
        }
    }

    public function notes($id, $token,$type = null, $note = null): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        if ($type = !null && $note != null) {
            return $this->Request("/students/$id/notes/$type/read/$note");
        } else {
            return $this->Request("/students/$id/notes/all");
        }
    }

    public function noticeBoard($id, $token,bool $mode = null, $fileNum = null, $eventCode = null, $pubID = null): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        // If mode == 1 read, else attach
        if ($mode != null) {
            if ($mode) {
                return $this->postRequest("/students/$id/noticeboard/read/$eventCode/$pubID/101",[]);
            } elseif ($fileNum != null) {
                return $this->Request("/students/$id/noticeboard/attach/$eventCode/$pubID/$fileNum",[]);
            }
        }
        return $this->Request("/students/$id/noticeboard");
    }

    public function periods($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/periods");
    }

    public function subjects($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/subjects");
    }

    public function schoolbooks($id, $token): bool|string
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Z-Dev-Apikey: Tg1NWEwNGIgIC0K',
            'User-Agent: CVVS/std/4.1.7 Android/10',
            'Z-Auth-Token: ' . $token,
        ));
        return $this->Request("/students/$id/schoolbooks");
    }

    // Start non-requests methods
    public static function convertClassevivaAgenda(string $classevivaAgenda): array
    {
        $classevivaAgenda = json_decode($classevivaAgenda);
        $classevivaEvents = array();

        foreach ($classevivaAgenda->agenda as $event) {
            $convertedEvent = new ClassevivaEvent(
                $event->evtId,
                $event->evtCode,
                $event->evtDatetimeBegin,
                $event->evtDatetimeEnd,
                $event->notes,
                $event->authorName,
                $event->classDesc,
                $event->subjectId,
                $event->subjectDesc
            );

            $classevivaEvents[] = $convertedEvent;
        }

        return $classevivaEvents;
    }
}
