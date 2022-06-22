import pathlib
from dataclasses import dataclass
from typing import List


@dataclass
class LogEvent:
    timestamp: str
    ip: str
    url: str
    ref: str
    agent: str


def readEventsFromFile(filePath: pathlib.Path) -> List[LogEvent]:
    events = []
    with open(filePath) as f:
        lines = f.readlines()
    for line in lines:
        parts = line.split(" ", 4)
        event = LogEvent(parts[0], parts[1], parts[2], parts[3], parts[4])
        events.append(event)
    return events



if __name__ == "__main__":

    rootFolderPath = pathlib.Path(__file__).parent.parent.parent
    filePath = rootFolderPath.joinpath("htdocs/logs/2022-06-21.txt")
    events = readEventsFromFile(filePath)
    makePage(events)

    print("DONE")
