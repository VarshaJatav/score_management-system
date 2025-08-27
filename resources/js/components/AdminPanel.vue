<template>
  <div class="p-6 bg-gray-900 text-white min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-center">Admin Panel - Manage Match</h1>

    <div class="bg-gray-800 p-4 rounded-lg shadow mb-8">
      <h2 class="text-xl font-semibold mb-4">Update Set Scores</h2>
      <div v-for="s in sets" :key="s.set_number" class="flex items-center gap-4 mb-3">
        <span class="w-20 font-semibold">Set {{ s.set_number }}</span>
        <input type="number" v-model.number="s.team_a_score" class="p-2 rounded text-black w-20"/>
        <span class="font-bold">-</span>
        <input type="number" v-model.number="s.team_b_score" class="p-2 rounded text-black w-20"/>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="s.is_completed" />
          <span>Completed</span>
        </label>
      </div>
      <button @click="saveScores" class="mt-3 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold">
        Save Scores
      </button>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-3">Home Team Stats</h2>
        <div v-for="(val,key) in homeStats" :key="key" class="flex items-center gap-2 mb-2">
          <label class="w-24 capitalize">{{ key }}:</label>
          <input type="number" v-model.number="homeStats[key]" class="p-2 rounded text-black w-24"/>
        </div>
      </div>

      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-3">Away Team Stats</h2>
        <div v-for="(val,key) in awayStats" :key="key" class="flex items-center gap-2 mb-2">
          <label class="w-24 capitalize">{{ key }}:</label>
          <input type="number" v-model.number="awayStats[key]" class="p-2 rounded text-black w-24"/>
        </div>
      </div>
    </div>
    <button @click="saveStats" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-semibold">
      Save Stats
    </button>

    <div class="grid grid-cols-2 gap-8 mt-10">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-3">Home Lineup</h2>
        <div v-for="p in homeLineup" :key="p.position_number" class="flex items-center gap-2 mb-2">
          <input v-model="p.name" placeholder="Name" class="p-2 rounded text-black flex-1"/>
          <input v-model="p.jersey" placeholder="Jersey" class="p-2 rounded text-black w-20"/>
        </div>
      </div>

      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-3">Away Lineup</h2>
        <div v-for="p in awayLineup" :key="p.position_number" class="flex items-center gap-2 mb-2">
          <input v-model="p.name" placeholder="Name" class="p-2 rounded text-black flex-1"/>
          <input v-model="p.jersey" placeholder="Jersey" class="p-2 rounded text-black w-20"/>
        </div>
      </div>
    </div>
    <button @click="saveLineup" class="bg-purple-500 hover:bg-purple-600 px-4 py-2 mt-6 rounded-lg font-semibold">
      Save Lineups
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  matchId: { type: [Number, String], required: true }
})

const sets = ref([])
const homeStats = ref({ kills:0, digs:0, aces:0, service:0, assists:0, blocks:0 })
const awayStats = ref({ kills:0, digs:0, aces:0, service:0, assists:0, blocks:0 })
const homeLineup = ref([])
const awayLineup = ref([])
const homeTeamId = ref(null)
const awayTeamId = ref(null)

async function loadData() {
  const { data } = await axios.get(`/api/scoreboard/${props.matchId}`)

  sets.value = data.sets.map(s => ({
    set_number: s.set_number,
    team_a_score: s.a,
    team_b_score: s.b,
    is_completed: s.is_completed
  }))

  homeStats.value = { ...data.teams.a.totals }
  awayStats.value = { ...data.teams.b.totals }

  homeLineup.value = [...data.teams.a.lineup]
  awayLineup.value = [...data.teams.b.lineup]

  homeTeamId.value = data.teams.a.id
  awayTeamId.value = data.teams.b.id
}

async function saveScores() {
  await axios.patch(`/api/matches/${props.matchId}/score`, { sets: sets.value })
}

async function saveStats() {
  await axios.patch(`/api/matches/${props.matchId}/stats`, {
    team_stats: [
      { team_id: homeTeamId.value, ...homeStats.value },
      { team_id: awayTeamId.value, ...awayStats.value }
    ]
  })
}

async function saveLineup() {
  await axios.patch(`/api/matches/${props.matchId}/lineups`, {
    lineups: [
      { team_id: homeTeamId.value, players: homeLineup.value },
      { team_id: awayTeamId.value, players: awayLineup.value }
    ]
  })
}

onMounted(loadData)
</script>
