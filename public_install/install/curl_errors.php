<?php

/**
 * PHP - FolioFrame
 *
 * A PHP MVC mini-framework using modern PHP design security, 
 * with the intent of usage for online portfolios, personal webpages,
 * small business sites, etc.. 
 *
 * See README.md for more information 
 *     --> https://github.com/gavbro/php-folioframe/blob/main/README.md
 *
 *
 * @package    php-folioframe
 * @copyright  2014-2021 Gavin Brown
 * @license    MIT License through GITHUB
 * @git        https://github.com/gavbro/php-folioframe
 * @link       https://gavinbrown.ca/
 * @since      See the README for current overall version info.
 * @source	   https://curl.se/libcurl/c/libcurl-errors.html <-- Text description source.
 *
 * @file       version 0.0.1
 *
 * @author Gavin Brown <gavin@gavinbrown.ca>
 *
 */

return array("1" => array(0 => "CURLE_UNSUPPORTED_PROTOCOL", 1 => "The URL you passed to libcurl used a protocol that this libcurl does not support. The support might be a compile-time option that you didn't use, it can be a misspelled protocol string or just a protocol libcurl has no code for."),  "2" => array(0 => "CURLE_FAILED_INIT", 1 => "Early initialization code failed. This is likely to be an internal error or problem, or a resource problem where something fundamental couldn't get done at init time."),  "3" => array(0 => "CURLE_URL_MALFORMAT", 1 => "The URL was not properly formatted."),  "4" => array(0 => "CURLE_NOT_BUILT_IN", 1 => "A requested feature, protocol or option was not found built-in in this libcurl due to a build-time decision. This means that a feature or option was not enabled or explicitly disabled when libcurl was built and in order to get it to function you have to get a rebuilt libcurl."),  "5" => array(0 => "CURLE_COULDNT_RESOLVE_PROXY", 1 => "Couldn't resolve proxy. The given proxy host could not be resolved."),  "6" => array(0 => "CURLE_COULDNT_RESOLVE_HOST", 1 => "Couldn't resolve host. The given remote host was not resolved."),  "7" => array(0 => "CURLE_COULDNT_CONNECT", 1 => "Failed to connect() to host or proxy."),  "8" => array(0 => "CURLE_WEIRD_SERVER_REPLY", 1 => "The server sent data libcurl couldn't parse. This error code was known as as CURLE_FTP_WEIRD_SERVER_REPLY before 7.51.0."),  "9" => array(0 => "CURLE_REMOTE_ACCESS_DENIED", 1 => "We were denied access to the resource given in the URL. For FTP, this occurs while trying to change to the remote directory."),  "10" => array(0 => "CURLE_FTP_ACCEPT_FAILED", 1 => "While waiting for the server to connect back when an active FTP session is used, an error code was sent over the control connection or similar."),  "11" => array(0 => "CURLE_FTP_WEIRD_PASS_REPLY", 1 => "After having sent the FTP password to the server, libcurl expects a proper reply. This error code indicates that an unexpected code was returned."),  "12" => array(0 => "CURLE_FTP_ACCEPT_TIMEOUT", 1 => "During an active FTP session while waiting for the server to connect, the CURLOPT_ACCEPTTIMEOUT_MS (or the internal default) timeout expired."),  "13" => array(0 => "CURLE_FTP_WEIRD_PASV_REPLY", 1 => "libcurl failed to get a sensible result back from the server as a response to either a PASV or a EPSV command. The server is flawed."),  "14" => array(0 => "CURLE_FTP_WEIRD_227_FORMAT", 1 => "FTP servers return a 227-line as a response to a PASV command. If libcurl fails to parse that line, this return code is passed back."),  "15" => array(0 => "CURLE_FTP_CANT_GET_HOST", 1 => "An internal failure to lookup the host used for the new connection."),  "16" => array(0 => "CURLE_HTTP2", 1 => "A problem was detected in the HTTP2 framing layer. This is somewhat generic and can be one out of several problems, see the error buffer for details."),  "17" => array(0 => "CURLE_FTP_COULDNT_SET_TYPE", 1 => "Received an error when trying to set the transfer mode to binary or ASCII."),  "18" => array(0 => "CURLE_PARTIAL_FILE", 1 => "A file transfer was shorter or larger than expected. This happens when the server first reports an expected transfer size, and then delivers data that doesn't match the previously given size."),  "19" => array(0 => "CURLE_FTP_COULDNT_RETR_FILE", 1 => "This was either a weird reply to a 'RETR' command or a zero byte transfer complete."),  "21" => array(0 => "CURLE_QUOTE_ERROR", 1 => "When sending custom \"QUOTE\" commands to the remote server, one of the commands returned an error code that was 400 or higher (for FTP) or otherwise indicated unsuccessful completion of the command."),  "22" => array(0 => "CURLE_HTTP_RETURNED_ERROR", 1 => "This is returned if CURLOPT_FAILONERROR is set TRUE and the HTTP server returns an error code that is >= 400."),  "23" => array(0 => "CURLE_WRITE_ERROR", 1 => "An error occurred when writing received data to a local file, or an error was returned to libcurl from a write callback."),  "25" => array(0 => "CURLE_UPLOAD_FAILED", 1 => "Failed starting the upload. For FTP, the server typically denied the STOR command. The error buffer usually contains the server's explanation for this."),  "26" => array(0 => "CURLE_READ_ERROR", 1 => "There was a problem reading a local file or an error returned by the read callback."),  "27" => array(0 => "CURLE_OUT_OF_MEMORY", 1 => "A memory allocation request failed. This is serious badness and things are severely screwed up if this ever occurs."),  "28" => array(0 => "CURLE_OPERATION_TIMEDOUT", 1 => "Operation timeout. The specified time-out period was reached according to the conditions."),  "30" => array(0 => "CURLE_FTP_PORT_FAILED", 1 => "The FTP PORT command returned error. This mostly happens when you haven't specified a good enough address for libcurl to use. See CURLOPT_FTPPORT."),  "31" => array(0 => "CURLE_FTP_COULDNT_USE_REST", 1 => "The FTP REST command returned error. This should never happen if the server is sane."),  "33" => array(0 => "CURLE_RANGE_ERROR", 1 => "The server does not support or accept range requests."),  "34" => array(0 => "CURLE_HTTP_POST_ERROR", 1 => "This is an odd error that mainly occurs due to internal confusion."),  "35" => array(0 => "CURLE_SSL_CONNECT_ERROR", 1 => "A problem occurred somewhere in the SSL/TLS handshake. You really want the error buffer and read the message there as it pinpoints the problem slightly more. Could be certificates (file formats, paths, permissions), passwords, and others."),  "36" => array(0 => "CURLE_BAD_DOWNLOAD_RESUME", 1 => "The download could not be resumed because the specified offset was out of the file boundary."),  "37" => array(0 => "CURLE_FILE_COULDNT_READ_FILE", 1 => "A file given with FILE:// couldn't be opened. Most likely because the file path doesn't identify an existing file. Did you check file permissions?"),  "38" => array(0 => "CURLE_LDAP_CANNOT_BIND", 1 => "LDAP cannot bind. LDAP bind operation failed."),  "39" => array(0 => "CURLE_LDAP_SEARCH_FAILED", 1 => "LDAP search failed."),  "41" => array(0 => "CURLE_FUNCTION_NOT_FOUND", 1 => "Function not found. A required zlib function was not found."),  "42" => array(0 => "CURLE_ABORTED_BY_CALLBACK", 1 => "Aborted by callback. A callback returned \"abort\" to libcurl."),  "43" => array(0 => "CURLE_BAD_FUNCTION_ARGUMENT", 1 => "A function was called with a bad parameter."),  "45" => array(0 => "CURLE_INTERFACE_FAILED", 1 => "Interface error. A specified outgoing interface could not be used. Set which interface to use for outgoing connections' source IP address with CURLOPT_INTERFACE."),  "47" => array(0 => "CURLE_TOO_MANY_REDIRECTS", 1 => "Too many redirects. When following redirects, libcurl hit the maximum amount. Set your limit with CURLOPT_MAXREDIRS."),  "48" => array(0 => "CURLE_UNKNOWN_OPTION", 1 => "An option passed to libcurl is not recognized/known. Refer to the appropriate documentation. This is most likely a problem in the program that uses libcurl. The error buffer might contain more specific information about which exact option it concerns."),  "49" => array(0 => "CURLE_SETOPT_OPTION_SYNTAX", 1 => "An option passed in to a setopt was wrongly formatted. See error message for details about what option."),  "52" => array(0 => "CURLE_GOT_NOTHING", 1 => "Nothing was returned from the server, and under the circumstances, getting nothing is considered an error."),  "53" => array(0 => "CURLE_SSL_ENGINE_NOTFOUND", 1 => "The specified crypto engine wasn't found."),  "54" => array(0 => "CURLE_SSL_ENGINE_SETFAILED", 1 => "Failed setting the selected SSL crypto engine as default!"),  "55" => array(0 => "CURLE_SEND_ERROR", 1 => "Failed sending network data."),  "56" => array(0 => "CURLE_RECV_ERROR", 1 => "Failure with receiving network data."),  "58" => array(0 => "CURLE_SSL_CERTPROBLEM", 1 => "problem with the local client certificate."),  "59" => array(0 => "CURLE_SSL_CIPHER", 1 => "Couldn't use specified cipher."),  "60" => array(0 => "CURLE_PEER_FAILED_VERIFICATION", 1 => "The remote server's SSL certificate or SSH md5 fingerprint was deemed not OK. This error code has been unified with CURLE_SSL_CACERT since 7.62.0. Its previous value was 51."),  "61" => array(0 => "CURLE_BAD_CONTENT_ENCODING", 1 => "Unrecognized transfer encoding."),  "62" => array(0 => "CURLE_LDAP_INVALID_URL", 1 => "Invalid LDAP URL."),  "63" => array(0 => "CURLE_FILESIZE_EXCEEDED", 1 => "Maximum file size exceeded."),  "64" => array(0 => "CURLE_USE_SSL_FAILED", 1 => "Requested FTP SSL level failed."),  "65" => array(0 => "CURLE_SEND_FAIL_REWIND", 1 => "When doing a send operation curl had to rewind the data to retransmit, but the rewinding operation failed."),  "66" => array(0 => "CURLE_SSL_ENGINE_INITFAILED", 1 => "Initiating the SSL Engine failed."),  "67" => array(0 => "CURLE_LOGIN_DENIED", 1 => "The remote server denied curl to login (Added in 7.13.1)"),  "68" => array(0 => "CURLE_TFTP_NOTFOUND", 1 => "File not found on TFTP server."),  "69" => array(0 => "CURLE_TFTP_PERM", 1 => "Permission problem on TFTP server."),  "70" => array(0 => "CURLE_REMOTE_DISK_FULL", 1 => "Out of disk space on the server."),  "71" => array(0 => "CURLE_TFTP_ILLEGAL", 1 => "Illegal TFTP operation."),  "72" => array(0 => "CURLE_TFTP_UNKNOWNID", 1 => "Unknown TFTP transfer ID."),  "73" => array(0 => "CURLE_REMOTE_FILE_EXISTS", 1 => "File already exists and will not be overwritten."),  "74" => array(0 => "CURLE_TFTP_NOSUCHUSER", 1 => "This error should never be returned by a properly functioning TFTP server."),  "75" => array(0 => "CURLE_CONV_FAILED", 1 => "Character conversion failed."),  "76" => array(0 => "CURLE_CONV_REQD", 1 => "Caller must register conversion callbacks."),  "77" => array(0 => "CURLE_SSL_CACERT_BADFILE", 1 => "Problem with reading the SSL CA cert (path? access rights?)"),  "78" => array(0 => "CURLE_REMOTE_FILE_NOT_FOUND", 1 => "The resource referenced in the URL does not exist."),  "79" => array(0 => "CURLE_SSH", 1 => "An unspecified error occurred during the SSH session."),  "80" => array(0 => "CURLE_SSL_SHUTDOWN_FAILED", 1 => "Failed to shut down the SSL connection."),  "81" => array(0 => "CURLE_AGAIN", 1 => "Socket is not ready for send/recv wait till it's ready and try again. This return code is only returned from curl_easy_recv and curl_easy_send (Added in 7.18.2)"),  "82" => array(0 => "CURLE_SSL_CRL_BADFILE", 1 => "Failed to load CRL file (Added in 7.19.0)"),  "83" => array(0 => "CURLE_SSL_ISSUER_ERROR", 1 => "Issuer check failed (Added in 7.19.0)"),  "84" => array(0 => "CURLE_FTP_PRET_FAILED", 1 => "The FTP server does not understand the PRET command at all or does not support the given argument. Be careful when using CURLOPT_CUSTOMREQUEST, a custom LIST command will be sent with PRET CMD before PASV as well. (Added in 7.20.0)"),  "85" => array(0 => "CURLE_RTSP_CSEQ_ERROR", 1 => "Mismatch of RTSP CSeq numbers."),  "86" => array(0 => "CURLE_RTSP_SESSION_ERROR", 1 => "Mismatch of RTSP Session Identifiers."),  "87" => array(0 => "CURLE_FTP_BAD_FILE_LIST", 1 => "Unable to parse FTP file list (during FTP wildcard downloading)."),  "88" => array(0 => "CURLE_CHUNK_FAILED", 1 => "Chunk callback reported error."),  "89" => array(0 => "CURLE_NO_CONNECTION_AVAILABLE", 1 => "(For internal use only, will never be returned by libcurl) No connection available, the session will be queued. (added in 7.30.0)"),  "90" => array(0 => "CURLE_SSL_PINNEDPUBKEYNOTMATCH", 1 => "Failed to match the pinned key specified with CURLOPT_PINNEDPUBLICKEY."),  "91" => array(0 => "CURLE_SSL_INVALIDCERTSTATUS", 1 => "Status returned failure when asked with CURLOPT_SSL_VERIFYSTATUS."),  "92" => array(0 => "CURLE_HTTP2_STREAM", 1 => "Stream error in the HTTP/2 framing layer."),  "93" => array(0 => "CURLE_RECURSIVE_API_CALL", 1 => "An API function was called from inside a callback."),  "94" => array(0 => "CURLE_AUTH_ERROR", 1 => "An authentication function returned an error."),  "95" => array(0 => "CURLE_HTTP3", 1 => "A problem was detected in the HTTP/3 layer. This is somewhat generic and can be one out of several problems, see the error buffer for details."),  "96" => array(0 => "CURLE_QUIC_CONNECT_ERROR", 1 => "QUIC connection error. This error may be caused by an SSL library error. QUIC is the protocol used for HTTP/3 transfers."),  "98" => array(0 => "CURLE_SSL_CLIENTCERT", 1 => "SSL Client Certificate required. "));