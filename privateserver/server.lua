-------------------------------------------------
-- Scripted By Central Gaming Development Team
-- Developer(s): Ranger
-- 2017(C) Copyrighted
-------------------------------------------------
local whitelist = {
	["11000010be44e8d"] = true,

}

RegisterServerEvent('playerConnecting')
AddEventHandler('playerConnecting', function(name, setCallback)
	local identifiers = GetPlayerIdentifiers(source)
	for i = 1, #identifiers do
        if(string.find(identifiers[i], "steam"))then
			local steamID = string.sub(identifiers[i], 7)
			print("Non Whitelist Connection: " .. steamID)
			
			if(whitelist[steamID] == true)then
				print("Whitelist Connection: " .. steamID)
				return
			end
        end
	end
	
	setCallback("Private Server. You will need to be whitelisted to connect.")
	CancelEvent()
end)