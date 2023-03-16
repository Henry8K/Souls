![Banner](file:///Design%20sem%20nome%20%2835%29.png)

## 💀 • Souls

> Welcome to the Souls plugin! This plugin is designed to enhance your server by introducing a Souls system. With its fully customizable API and commands, you can easily manage and configure the plugin according to your preferences using a YAML configuration file. The Souls system provides a unique and engaging experience for your players, allowing them to earn Souls for completing various tasks and challenges within your server. Implementing this system can help improve player engagement and retention, and enhance the overall gameplay experience on your server.


## 🌴 • API

> This is the default api for any plugin you are going to use Souls as a base, remember to define a dependency on ```plugin.yml``` so that there are no internal errors, if you are unable to use the API create an issue for so that we can solve your problem: 

```php
use pocketmine\player\Player;
use Henry8K\Souls\API\SoulsAPI;

class MyPlugin extends PluginBase {

    /** @var SoulsAPI */
    private $soulsAPI;

    public function onEnable(): void {
        $this->soulsAPI = new SoulsAPI($this);
    }

    public function myPluginFunction(Player $player) {
        
        $amount = 10;
        
        // add souls to player.
        $this->soulsAPI->addSouls($player, $amount);

        // get all player souls.
        $this->soulsAPI->getSouls($player);

        // remove player souls.
        $this->soulsAPI->removeSouls($player, $amount);

        // set player souls.
        $this->soulsAPI->setSouls($player, $amount);
    }
}
```aa

## 🧭 • General

> To provide this plugin publicly all customized and well done it took me around 4 hours to do all the code and another 5 hours to customize the rest of the plugin, I hope you like it and use it a lot to customize your server with new functions that until now were famous only in Java, I intend to continue on this path of transforming java plugins into php. Made with heart by Henry8K