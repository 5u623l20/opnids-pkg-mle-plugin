<?php
/*
 * Copyright (C) 2018 CounterFlow AI, Inc
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

 function dragonflymle_enabled()
{
    global $config;
    return isset($config['OPNsense']['MLE']['enabled']) &&
        $config['OPNsense']['MLE']['enabled'] == '1';
}

function dragonflymle_services()
{
    $services = array();

    if (!dragonflymle_enabled()) {	
        return $services;	
    }

    $services[] = array(
        'name' => 'dragonfly-mle',
        'description' => gettext('Dragonfly MLE Daemon'),
        'configd' => array(
            'start' => array('dragonflymle start'),
            'stop' => array('dragonflymle stop'),
            'restart' => array('dragonflymle restart'),
        ),
        'pidfile' => '/var/run/dragonfly-mle.pid',
    );
    return $services;
}