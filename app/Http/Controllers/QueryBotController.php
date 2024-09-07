<?php

namespace App\Http\Controllers;

use App\Models\AgentPerformance;
use App\Models\CallLog;
use App\Models\TargetAchievement;
use Illuminate\Http\Request;

class QueryBotController extends Controller
{
    public function handleQuery(Request $request)
    {
        $query = $request->input('query');

        // Define keywords to handler methods
        $handlers = [
            'calls' => 'handleCallLogs',
            'logs' => 'handleCallLogs',
            'agent performance' => 'handleAgentPerformance',
            'performance' => 'handleAgentPerformance',
            'progress' => 'handleAgentPerformance',
            'targets' => 'handleTargetsAchievements',
            'achievements' => 'handleTargetsAchievements',
            'salled' => 'handleTargetsAchievements',
            'statics' => 'handleTargetsAchievements',
            'hello' => 'handleGreeting',
            'hi' => 'handleGreeting',
        ];

        // Lowercase the query for matching
        $lowerCaseQuery = strtolower($query);

        // Match Query to handler
        foreach ($handlers as $keyword => $method) {
            if (strpos($lowerCaseQuery, $keyword) !== false) {
                return $this->$method($query);
            }
        }

        return response()->json(['error' => 'Invalid query'], 400);
    }

    public function handleGreeting()
    {
        return response()->json(['message' => 'Welcome to Query Bot, How can I help you?'], 200);
    }

    public function handleCallLogs($query)
    {
        if (preg_match('/missed/i', $query)) {
            $callLogs = CallLog::where('call_status', 'missed')->latest()->take(10)->get();
        }
        elseif (preg_match('/answered/i', $query)) {
            $callLogs = CallLog::where('call_status', 'answered')->latest()->take(10)->get();
        }else{
            $callLogs = CallLog::latest()->get();
        }

        // Converting call_duration from intigers to time period (00:05, 02:03)
        foreach ($callLogs as $log) {
            $hours = intdiv($log->call_duration, 60);
            $minutes = $log->call_duration % 60;
            $log->call_duration = sprintf('%02d:%02d', $hours, $minutes);
        }
        $callLogs['count'] = $callLogs->count();
        return response()->json(['callLogs' => $callLogs]);
    }

    public function handleAgentPerformance($query)
    {
        if (preg_match('/agent id (\d+)/i', $query, $matches)) {
            $agentId = $matches[1];
            $performance = AgentPerformance::where('agent_id', $agentId)->latest()->get();

            foreach ($performance as $data) {
                $hours = intdiv($data->average_call_duration, 60);
                $minutes = $data->average_call_duration % 60;
                $data->average_call_duration = sprintf('%02d:%02d', $hours, $minutes);
            }

            if ($performance->isEmpty()) {
                return response()->json(['error' => 'No performance data found for the given agent ID'], 404);
            } else {
                return response()->json(['performance' => $performance]);
            }
        } else {
            $performance = AgentPerformance::latest()->take(10)->get();
            return response()->json(['Performance' => $performance], 200);
        }
    }

    public function handleTargetsAchievements($query)
    {
        if (preg_match('/agent id (\d+)/i', $query, $matches)) {
            $agentId = $matches[1];
            $targets = TargetAchievement::where('agent_id', $agentId)->latest()->take(10)->get();
            if ($targets->isEmpty()) {
                return response()->json(['error' => 'No targets data found for the given agent ID'], 404);
            } else {
                return response()->json(['targets' => $targets]);
            }
        }else{
            $targets = TargetAchievement::latest()->get();
        }
        return response()->json(['targets' => $targets]);
    }

    public function getCallLogs()
    {
        $callLogs = CallLog::latest()->get();
        return $callLogs ? response()->json(['callLogs' => $callLogs]) : response()->json(['message' => 'There is no call logs data yet!']);
    }

    public function getAgentPerformance() {
        $targets = AgentPerformance::latest()->get();
        return $targets ? response()->json(['targets' => $targets]) : response()->json(['message' => 'There is no Agents Performances data yet!']);
    }
    public function getTargetsAchievements() {
        $performance = TargetAchievement::latest()->get();
        return $performance ? response()->json(['Performance' => $performance], 200) : response()->json(['message' => 'There is no Targets and Achievements data yet!']);
    }

}
