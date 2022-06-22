import pathlib
from typing import List
import reportgen
from reportgen import Event


def readEventsFromFile(filePath: pathlib.Path) -> List[Event]:
    events = []
    with open(filePath) as f:
        lines = f.readlines()
    for line in lines:
        parts = line.split(" ", 4)
        event = Event(parts[0], parts[1], parts[2], parts[3], parts[4])
        events.append(event)
    return events


if __name__ == "__main__":

    rootFolderPath = pathlib.Path(__file__).parent.parent.parent
    filePath = rootFolderPath.joinpath("htdocs/logs/2022-06-21.txt")
    events = readEventsFromFile(filePath)

    saveAs = pathlib.Path(__file__).parent.joinpath("out.html")
    reportgen.pages.makeDailyPage(events, saveAs)

    print(saveAs)
