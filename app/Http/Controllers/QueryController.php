<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function showForm()
    {
        return view('query');
    }

    public function convertQueryForm() {
        return view('query-converter');
    }

    public function runQuery(Request $request)
    {
        $mysql_query = $request->input('mysql_query');
        $pgsql_query = $request->input('pgsql_query');
        $result = [];

        if (!empty($mysql_query)) {
            try {
                // Split MySQL queries
                $mysql_queries = explode(';', $mysql_query);
                foreach ($mysql_queries as $query) {
                    $query = trim($query);
                    if (empty($query)) {
                        continue;
                    }
                    // Run query on MySQL
                    DB::connection('mysql')->statement($query);
                    $result['mysql'][] = "Query executed successfully: $query";
                    info('Query execution success Mysql', ['query' => $query]);
                }
            } catch (\Exception $e) {
                $result['mysql'][] = "Error executing query: $query. Error: " . $e->getMessage();
                info('Query execution Failed Mysql', ['error' => $e->getMessage()]);
            }
        }

        if (!empty($pgsql_query)) {
            try {
                // Split PostgreSQL queries
                $pgsql_queries = explode(';', $pgsql_query);
                foreach ($pgsql_queries as $query) {
                    $query = trim($query);
                    if (empty($query)) {
                        continue;
                    }
                    // Run query on PostgreSQL
                    DB::connection('pgsql')->statement($query);
                    $result['pgsql'][] = "Query executed successfully: $query";
                    info('Query execution success Pgsql', ['query' => $query]);
                }
            } catch (\Exception $e) {
                $result['pgsql'][] = "Error executing query: $query. Error: " . $e->getMessage();
                info('Query execution Failed PgSql', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->back()->with('result', $result);
    }

    public function convertQuery(Request $request) {
        $mysqlQuery = $request->input('query');
        $convertedQuery = '';

        try {
            $convertedQuery = $this->convertMysqlToPgsql($mysqlQuery);
        } catch (\Exception $e) {
            $convertedQuery = 'Error: ' . $e->getMessage();
        }

        return redirect()->back()->with('convertedQuery', $convertedQuery);
    }

    private function convertMysqlToPgsql($mysqlQuery)
    {
        // Conversion logic here
        $pgsqlQuery = $mysqlQuery;

        // Basic conversions
        $pgsqlQuery = str_replace('`', '"', $pgsqlQuery); // Convert backticks to double quotes
        $pgsqlQuery = preg_replace('/AUTO_INCREMENT/i', 'SERIAL', $pgsqlQuery); // Convert AUTO_INCREMENT to SERIAL

        // Convert data types
        $pgsqlQuery = preg_replace('/int\([0-9]+\)/i', 'INTEGER', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/tinyint\([0-9]+\)/i', 'SMALLINT', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/varchar\(([0-9]+)\)/i', 'VARCHAR($1)', $pgsqlQuery);
        $pgsqlQuery = preg_replace('/datetime/i', 'TIMESTAMP', $pgsqlQuery);

        // Convert comment syntax
        $pgsqlQuery = preg_replace('/--(.*)/', '/*$1*/', $pgsqlQuery);

        // Additional conversions can be added here

        return $pgsqlQuery;
    }
}
